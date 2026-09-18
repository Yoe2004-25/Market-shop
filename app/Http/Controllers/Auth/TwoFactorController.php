<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactor,
    ) {}

    // توليد QR
    public function setup(Request $request)
    {
        return response()->json(
            $this->twoFactor->generate($request->user())
        );
    }

    // تأكيد OTP لتفعيل 2FA
    public function confirm(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $user = $request->user();

        if (! $this->twoFactor->verify($user, $request->otp)) {
            return response()->json(['message' => 'رمز غير صحيح.'], 422);
        }

        $this->twoFactor->confirm($user);

        return response()->json(['message' => 'تم تفعيل 2FA بنجاح.']);
    }
}