<?php

namespace App\Actions\CheckAuth ; 

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
        ]);

        // إسناد دور افتراضي (Spatie)
        $user->assignRole($data['role'] ?? 'customer');

        return $user;
    }
}