<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = \Auth::user();
        $data = parent::toArray($request);
        $data['user_name'] = $user->name;
        $data['date'] = Carbon::parse($data['created_at'])
            ->setTimezone($user->timezone)
            ->format('Y-m-d H:i:s');

        return collect($data)->except(['created_at'])->toArray();

    }
}
