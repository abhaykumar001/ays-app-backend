<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register: validate details, store pending OTP record, send email.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'name'           => $request->name,
                'plain_password' => $request->password,
                'otp'            => $otp,
                'expires_at'     => now()->addMinutes(10),
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp, $request->name));

        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.',
            'data'    => ['email' => $request->email],
        ]);
    }

    /**
     * Verify OTP: validate code, create the user, return token.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = EmailOtp::where('email', $request->email)->latest()->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'No pending registration found for this email. Please register again.',
            ], 422);
        }

        if ($record->isExpired()) {
            $record->delete();
            return response()->json([
                'success' => false,
                'message' => 'The verification code has expired. Please register again.',
            ], 422);
        }

        if ($record->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please try again.',
            ], 422);
        }

        $user = User::create([
            'name'     => $record->name,
            'email'    => $record->email,
            'password' => $record->plain_password,
        ]);

        $user->email_verified_at = now();
        $user->save();

        $user->assignRole('Client');
        $record->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Account verified and created successfully.',
            'data'    => [
                'token' => $token,
                'user'  => new UserResource($user),
            ],
        ], 201);
    }

    /**
     * Resend OTP: generate a fresh code and email it again.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $record = EmailOtp::where('email', $request->email)->latest()->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'No pending registration found for this email. Please register again.',
            ], 422);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $record->update([
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($record->email)->send(new OtpMail($otp, $record->name));

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.',
        ]);
    }

    /**
     * Authenticate and issue an API token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => "Your account was deleted. If you think this was a mistake, please write to us at support@aysdevelopers.ae and we'll get in touch with you shortly.",
            ], 403);
        }

        $user->tokens()->where('name', 'mobile')->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'data'    => [
                'token' => $token,
                'user'  => new UserResource($user),
            ],
        ]);
    }

    /**
     * Revoke the current token (logout).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Update the authenticated user's name/phone. Client accounts only.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->getRoleNames()->first() !== 'Client') {
            return response()->json([
                'success' => false,
                'message' => 'Only client accounts can update their details.',
            ], 403);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully.',
            'data'    => ['user' => new UserResource($user)],
        ]);
    }

    /**
     * Deactivate the authenticated user's account. Client accounts only.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->getRoleNames()->first() !== 'Client') {
            return response()->json([
                'success' => false,
                'message' => 'Only client accounts can be deleted.',
            ], 403);
        }

        $user->is_active = false;
        $user->save();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your account has been deleted successfully.',
        ]);
    }

    /**
     * Return the authenticated user's profile.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()),
        ]);
    }
}
