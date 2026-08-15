<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\NotificationCampaign;
use App\Models\Project;
use App\Models\ProjectOffer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class NotificationsController extends Controller
{
    public function index()
    {
        $campaigns = NotificationCampaign::withCount('notifications')->latest()->get();

        return view('dashboard.notifications.index', compact('campaigns'));
    }

    public function create()
    {
        return view('dashboard.notifications.create', [
            'roles'    => Role::whereIn('name', ['Client', 'Agent', 'Owner'])->pluck('name'),
            'projects' => Project::select('id', 'slug', 'name')->orderBy('name')->get(),
            'offers'   => ProjectOffer::with('project:id,name')->orderBy('title')->get(),
            'events'   => Event::select('id', 'slug', 'title')->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $campaign = NotificationCampaign::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $campaign->addMediaFromRequest('image')->toMediaCollection('image');
        }

        if ($data['status'] === 'draft') {
            return redirect()->route('notifications.index')
                ->with('success', 'Notification saved as draft.');
        }

        if ($data['status'] === 'scheduled') {
            return redirect()->route('notifications.index')
                ->with('success', 'Notification scheduled for ' . $campaign->scheduled_at->format('d M Y, h:i A') . '.');
        }

        // "Send Now"
        $campaign->dispatchSend();

        return redirect()->route('notifications.index')
            ->with('success', "Notification sent to {$campaign->fresh()->total_recipients} user(s).");
    }

    public function show(NotificationCampaign $notification)
    {
        $notification->load('creator');

        return view('dashboard.notifications.show', ['campaign' => $notification]);
    }

    public function destroy(NotificationCampaign $notification)
    {
        if (in_array($notification->status, ['sent', 'sending'], true)) {
            return back()->with('error', 'A sent notification cannot be deleted.');
        }

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted.');
    }

    /**
     * Sends a one-off test push to the current admin's own device tokens
     * (if any are registered) without creating a campaign — lets you
     * verify the Firebase setup end-to-end from the composer screen.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $tokens = $user->deviceTokens()->pluck('token')->all();

        if (empty($tokens)) {
            return back()->with('error', 'No device is registered to your admin account yet. Open the mobile app while logged in as this admin, then try again.');
        }

        $message = \Kreait\Firebase\Messaging\CloudMessage::new()
            ->withNotification(['title' => $request->title, 'body' => $request->message])
            ->withData(['notification_id' => '0', 'deep_link_type' => 'none', 'deep_link_value' => '']);

        \Kreait\Laravel\Firebase\Facades\Firebase::messaging()->sendMulticast($message, $tokens);

        return back()->with('success', 'Test notification sent.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'message'          => 'required|string',
            'type'             => ['required', Rule::in(NotificationCampaign::TYPES)],
            'target'           => ['required', Rule::in(NotificationCampaign::TARGET_TYPES)],
            'roles'            => 'required_if:target,role|array',
            'roles.*'          => 'string|in:Client,Agent,Owner',
            'deep_link_type'   => ['nullable', Rule::in(NotificationCampaign::DEEP_LINK_TYPES)],
            'deep_link_value'  => 'nullable|string|max:255',
            'priority'         => 'required|in:low,normal,high,urgent',
            'status'           => 'required|in:draft,scheduled,sent',
            'scheduled_at'     => 'required_if:status,scheduled|nullable|date|after:now',
            'image'            => 'nullable|image|max:5120',
        ]);

        if (($data['deep_link_type'] ?? 'none') === 'none') {
            $data['deep_link_value'] = null;
        }

        unset($data['image']);

        return $data;
    }
}
