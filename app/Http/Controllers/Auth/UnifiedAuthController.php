<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CheckAuth\LoginCheckAction;
use App\Actions\CheckAuth\RegisterAction;
use App\DTOs\LoginDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnifiedAuthController extends Controller
{
    public function __construct(
        protected LoginCheckAction $loginAction,
        protected RegisterAction $registerAction,
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'otp'      => 'nullable|string|size:6',
            'client'   => 'nullable|in:web,mobile,partner',
        ]);

        $result = $this->loginAction->execute(
            LoginDTO::fromRequest($request->all())
        );

        if (isset($result['requires_2fa'])) {
            return response()->json($result, 428);
        }

        return response()->json($result);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = $this->registerAction->execute($data);

        auth('web')->login($user);

        return response()->json(['user' => $user], 201);
    }
}