<?php

namespace App\Http\Resources;

use App\Models\Habit;
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
        $habit =Habit::find($data['id']) ;
        $data['days'] = $habit->getDays();
        if ($this->relationLoaded('habit_logs')) {
            $data['habit_logs'] = HabitLogResource::collection($data['habit_logs']);
        }
        return $data;
    }
}
