<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function createUser(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'password' => Hash::make($data['password']),
            'fcm_token' => $data['fcm_token'] ?? null,
            'email' => $data['email'],
            'photo' => isset($data['photo']) ? NewPublicPhoto($data['photo'], 'profiles') : null,
        ]);

        return $user;
    }

    public static function updatePassword($user, $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public static function updateUser($data): User
    {
        $user = Auth::user();
        if (isset($data['photo']) && $data['photo'] != null) {
            if ($user->photo) {
                DeletePublicPhoto($user->photo);
            }
            $data['photo'] = NewPublicPhoto($data['photo'], 'profiles');
        }

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'photo' => $data['photo'] ?? $user->photo,
        ]);

        return $user;
    }
}
