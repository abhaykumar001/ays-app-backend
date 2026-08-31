<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Mail\PasswordResetOtpMail;
use App\Models\EmailOtp;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Roles allowed to self-service reset their password from the app.
     * Internal Agent accounts are staff logins and must go through an
     * administrator instead — see forgotPassword() below.
     */
    private const SELF_SERVICE_RESET_ROLES = ['Client', 'External Agent', 'External Agency', 'Owner'];

    /**
     * Roles that self-register into a pending state and need admin approval
     * before they can log in — see verifyOtp() and login() below.
     */
    private const PENDING_APPROVAL_ROLES = ['External Agent', 'External Agency'];

    /**
     * Register: validate details, store pending OTP record, send email.
     * External Agent (broker) and External Agency registrations also carry
     * a company name + Official Registration Number; External Agency
     * additionally requires bank details and a Tax Registration Number.
     * All of this is held on the EmailOtp row until verifyOtp() creates the
     * real User record.
     */
    public function register(Request $request): JsonResponse
    {
        $role = $request->input('role') ?? 'Client';

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|string|in:Client,External Agent,External Agency',
            'company_name' => Rule::requiredIf(in_array($role, ['External Agent', 'External Agency'], true)) . '|string|max:255',
            'official_registration_number' => Rule::requiredIf(in_array($role, ['External Agent', 'External Agency'], true)) . '|string|max:100',
            'bank_name'      => Rule::requiredIf($role === 'External Agency') . '|string|max:255',
            'iban_number'    => Rule::requiredIf($role === 'External Agency') . '|string|max:50',
            'account_number' => Rule::requiredIf($role === 'External Agency') . '|string|max:50',
            'trn_number'     => Rule::requiredIf($role === 'External Agency') . '|string|max:50',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'name'           => $request->name,
                'phone'          => $request->phone,
                'plain_password' => $request->password,
                'role'           => $role,
                'otp'            => $otp,
                'expires_at'     => now()->addMinutes(10),
                'company_name'                  => $request->company_name,
                'official_registration_number'  => $request->official_registration_number,
                'bank_name'      => $request->bank_name,
                'iban_number'    => $request->iban_number,
                'account_number' => $request->account_number,
                'trn_number'     => $request->trn_number,
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

        $role = $record->role ?: 'Client';

        $user = User::create([
            'name'     => $record->name,
            'email'    => $record->email,
            'phone'    => $record->phone,
            'password' => $record->plain_password,
            'company_name'                 => $record->company_name,
            'official_registration_number' => $record->official_registration_number,
            'bank_name'      => $record->bank_name,
            'iban_number'    => $record->iban_number,
            'account_number' => $record->account_number,
            'trn_number'     => $record->trn_number,
        ]);

        $user->email_verified_at = now();

        // External Agent (broker) and External Agency accounts self-
        // registering from the app start out unapproved and can't log in
        // until an admin approves them from the dashboard — see
        // AuthController::login() and Dashboard\UserController::approve().
        if (in_array($role, self::PENDING_APPROVAL_ROLES, true)) {
            $user->is_approved = false;
        }

        $user->save();
        $user->assignRole($role);
        $record->delete();

        if ($role === 'External Agent') {
            // Short-lived, single-purpose token: lets the app make one
            // authenticated call to uploadBrokerDocuments() below before the
            // broker has a real session (no login token is issued until an
            // admin approves them). Deleted right after that upload.
            $uploadToken = $user->createToken('broker-doc-upload', ['broker:upload-documents'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Your account has been registered successfully.',
                'data'    => [
                    'pending_approval' => true,
                    'upload_token'     => $uploadToken,
                    'user'             => new UserResource($user),
                ],
            ], 201);
        }

        if ($role === 'External Agency') {
            // Same pattern as the broker upload token above, but Trade
            // License + owner identity document are mandatory for an
            // agency — see uploadAgencyDocuments() below.
            $uploadToken = $user->createToken('agency-doc-upload', ['agency:upload-documents'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Your account has been registered successfully.',
                'data'    => [
                    'pending_approval' => true,
                    'upload_token'     => $uploadToken,
                    'user'             => new UserResource($user),
                ],
            ], 201);
        }

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
     * Upload optional broker (External Agent) identity documents using the
     * single-use token issued by verifyOtp(). Both fields are optional —
     * either, both, or neither may be present. The token is revoked after
     * this call regardless of which files were sent.
     */
    public function uploadBrokerDocuments(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('External Agent') || ! $request->user()->currentAccessToken()->can('broker:upload-documents')) {
            return response()->json([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        $request->validate([
            'passport'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'emirates_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('passport')) {
            $user->addMediaFromRequest('passport')->toMediaCollection('passport');
        }

        if ($request->hasFile('emirates_id')) {
            $user->addMediaFromRequest('emirates_id')->toMediaCollection('emirates_id');
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documents uploaded successfully.',
        ]);
    }

    /**
     * Upload required External Agency registration documents (Trade License
     * + the owner's Passport/EID or Power of Attorney) using the single-use
     * token issued by verifyOtp(). Unlike uploadBrokerDocuments(), both
     * files are mandatory — this call must succeed before the agency's
     * registration is considered complete. The token is revoked after a
     * successful call; a validation failure leaves it intact so the app can
     * retry.
     */
    public function uploadAgencyDocuments(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('External Agency') || ! $request->user()->currentAccessToken()->can('agency:upload-documents')) {
            return response()->json([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        $request->validate([
            'trade_license'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'owner_identity_document'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'owner_document_type'      => 'required|string|in:passport_eid,poa',
        ]);

        $user->addMediaFromRequest('trade_license')->toMediaCollection('trade_license');
        $user->addMediaFromRequest('owner_identity_document')->toMediaCollection('owner_identity_document');
        $user->owner_document_type = $request->owner_document_type;
        $user->save();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documents uploaded successfully.',
        ]);
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

        if (! $user->is_approved) {
            return response()->json([
                'success' => false,
                'message' => "Your account is pending approval. We'll send you a confirmation email within 24 hours once it's activated — you can then log in with these same credentials.",
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
     * Forgot password (step 1): email an OTP to reset with.
     * Only Client / External Agent / Owner accounts may self-service reset —
     * Internal Agent (staff) accounts must contact an administrator.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email.',
            ], 404);
        }

        if (! $user->getRoleNames()->intersect(self::SELF_SERVICE_RESET_ROLES)->count()) {
            return response()->json([
                'success' => false,
                'message' => "Password reset isn't available for this account type. Please contact your administrator.",
            ], 403);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::updateOrCreate(
            ['email' => $user->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user->name));

        return response()->json([
            'success' => true,
            'message' => 'A password reset code has been sent to your email.',
            'data'    => ['email' => $user->email],
        ]);
    }

    /**
     * Forgot password (step 2): verify the OTP and set a new password in one
     * call. Revokes all existing sessions on success so a stolen/expired
     * token elsewhere can't keep using the old password's session.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'otp'      => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = PasswordResetOtp::where('email', $request->email)->latest()->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'No password reset was requested for this email. Please request a new code.',
            ], 422);
        }

        if ($record->isExpired()) {
            $record->delete();
            return response()->json([
                'success' => false,
                'message' => 'This code has expired. Please request a new one.',
            ], 422);
        }

        if ($record->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code. Please try again.',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $record->delete();
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email.',
            ], 404);
        }

        $user->password = $request->password;
        $user->save();
        $user->tokens()->delete();
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset. Please log in with your new password.',
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
