<?php

namespace App\Policies;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;

class HabitLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HabitLog $habitLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habit $habit, HabitLog $habitLog): bool
    {
        return $habit->user_id == $user->id && $habit->id == $habitLog->habit_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HabitLog $habitLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HabitLog $habitLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HabitLog $habitLog): bool
    {
        return false;
    }
}
