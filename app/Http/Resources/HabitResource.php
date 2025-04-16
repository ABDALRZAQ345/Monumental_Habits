<?php

namespace App\Http\Resources;

use App\Models\Habit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HabitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = parent::toArray($request);

        $habit = Habit::find($data['id']);

        if (isset($data['days']) && $data['days'] != null) {
            $data['days'] = $habit->getDays();
            if (count($data['days']) == 7) {
                $data['days'] = ['everyday'];
            }
        }
        if (isset($data['reminder_time']) && $data['reminder_time'] != null) {
            $time = DateTime::createFromFormat('H:i:s', $data['reminder_time']);
            $data['reminder_time'] = $time ? $time->format('h:i A') : null;
        }

        if ($this->relationLoaded('habit_logs')) {
            $data['habit_logs'] = HabitLogResource::collection($data['habit_logs']);
        }

        return $data;
    }
}
