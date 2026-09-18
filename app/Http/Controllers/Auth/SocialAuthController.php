<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;

class SocialAuthController extends Controller
{
    public function __construct(
        protected SocialAuthService $social,
    ) {}

    public function redirect(string $provider)
    {
        return \Laravel\Socialite\Facades\Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $socialUser = \Laravel\Socialite\Facades\Socialite::driver($provider)->user();

        $user = $this->social->findOrCreate($provider, $socialUser);

        $guard = auth('web');

        if ($guard instanceof \Illuminate\Auth\SessionGuard) {
            $guard->login($user, true);
        }

        return redirect('/dashboard');
    }
}