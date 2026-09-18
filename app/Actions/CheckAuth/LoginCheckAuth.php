<?php

namespace App\Actions\CheckAuth;

use App\DTOs\LoginDTO;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginCheckAuth
{
    public function __construct(
        protected TwoFactorService $twoFactor,
    ) {}

    public function execute(LoginDTO $dto): array
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة.'],
            ]);
        }

        // ============ 2FA Check ============
        if ($user->two_factor_confirmed_at) {
            if (! $dto->otp) {
                return [
                    'requires_2fa' => true,
                    'message'      => 'أدخل رمز المصادقة الثنائية.',
                ];
            }

            if (! $this->twoFactor->verify($user, $dto->otp)) {
                throw ValidationException::withMessages([
                    'otp' => ['رمز OTP غير صحيح.'],
                ]);
            }
        }

        // ============ إصدار التوكن حسب العميل ============
        return match ($dto->client) {
            'mobile'  => $this->issueJwt($user),
            'partner' => $this->issueSanctumToken($user),
            default   => $this->issueSession($user),
        };
    }

    private function issueJwt(User $user): array
    {
        $token = auth('api-mobile')->login($user);

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api-mobile')->factory()->getTTL() * 60,
            'user'         => $user,
        ];
    }

    private function issueSanctumToken(User $user): array
    {
        return [
            'access_token' => $user->createToken('partner-token')->plainTextToken,
            'token_type'   => 'bearer',
            'user'         => $user,
        ];
    }

    private function issueSession(User $user): array
    {
        auth('web')->login($user, true);

        return ['user' => $user];
    }
}