<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialAuthService
{
    public function findOrCreate(string $provider, object $socialUser): User
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // ربط الحساب بالمزود إن لم يكن مربوطًا
            if (! $user->provider) {
                $user->update([
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar'      => $socialUser->getAvatar(),
                ]);
            }

            return $user;
        }

        // إنشاء مستخدم جديد
        $user = User::create([
            'name'        => $socialUser->getName() ?? $socialUser->getNickname(),
            'email'       => $socialUser->getEmail(),
            'password'    => Hash::make(Str::random(32)),
            'provider'    => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar'      => $socialUser->getAvatar(),
        ]);

        $user->assignRole('customer');

        return $user;
    }
}