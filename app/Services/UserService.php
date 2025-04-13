<?php

namespace App\Services;

use App\Enums\Days;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function createUser(array $data): User
    {

        return User::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'fcm_token' => (!empty($data['fcm_token'])) ? $data['fcm_token'] : null,
            'email' => $data['email'],
            'photo' => ( isset($data['photo']) && (!empty($data['photo'])) ) ? NewPublicPhoto($data['photo'], 'profiles') : null,
            'timezone' => $data['timezone'],
        ]);
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
            'name' => $data['name'],
            'photo' => (!empty($data['photo'])) ? $data['photo']: $user->photo,
        ]);

        return $user;
    }

    public function ComplementInAWeek($user): array
    {

        $timezone = $user->timezone;
        $now = Carbon::now($timezone);
        $startOfWeek = $now->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(6);
        // getting all habits in that week
        $habits = $user->habits()->with([
            'habit_logs' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
            },
        ])->get();
        $completed = array_fill(0, 7, 0);
        $total = array_fill(0, 7, 0);
        // complexity is 20 (max_number_of_habits) * 7 (days of week ) = 140 which is very small and fast
        foreach ($habits as $habit) {
            foreach ($habit->habit_logs as $log) {
                if ($log->status === null) {
                    continue;
                }
                $dayIndex = Carbon::parse($log->date)->dayOfWeek;
                $total[$dayIndex]++;
                if ($log->status != 0) {
                    $completed[$dayIndex]++;
                }
            }
        }
        $data = [];
        $days = Days::getDaysArray();

        foreach ($days as $name => $value) {
            $percentage = $total[$value] != 0 ? $completed[$value] / $total[$value] : 0;
            $data[$name] = [
                "$completed[$value] / $total[$value]",
                $percentage,
            ];
        }

        return $data;
    }
}
