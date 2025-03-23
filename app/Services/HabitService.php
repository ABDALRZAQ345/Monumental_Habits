<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\User;

class HabitService
{
    public static function encodeDays(array $days): int
    {
        $map = [
            'Sunday' => 1 << 0, // 0000001 = 1
            'Monday' => 1 << 1, // 0000010 = 2
            'Tuesday' => 1 << 2, // 0000100 = 4
            'Wednesday' => 1 << 3, // 0001000 = 8
            'Thursday' => 1 << 4, // 0010000 = 16
            'Friday' => 1 << 5, // 0100000 = 32
            'Saturday' => 1 << 6, // 1000000 = 64
        ];
        $mask = 0;
        foreach ($days as $day) {
            $mask |= $map[$day] ?? 0;
        }

        return $mask;
    }

    public static function store(User $user, array $data): Habit
    {
        return Habit::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'days' => self::encodeDays($data['days']),
            'reminder_time' => $data['reminder_time'],
        ]);

    }

    public function CanCreateHabit($user,$name): array
    {
        if ($user->habits()->count() >= config('app.data.max_habits')) {
            return [
                'status' => false,
                'message' => 'Sorry, you can’t add more than '.config('app.data.max_habits').' habits.'
            ];
        }

        if ($user->habits()->where('name', $name)->exists()) {
            return [
                'status' => false,
                'message' => 'Habit already exists!'
            ];
        }

        return [
            'status' => true
        ];


    }

    public static function update(Habit $habit, array $data): Habit
    {
        $habit->update([
            'name' => $data['name'],
            'days' => self::encodeDays($data['days']),
            'reminder_time' => $data['reminder_time'],
        ]);

        return $habit;
    }
}
