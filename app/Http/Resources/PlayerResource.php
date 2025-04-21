<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'playerSkills' => $this->playerSkills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'skill' => $skill->skill,
                    'value' => $skill->value,
                    'playerId' => $skill->player_id
                ];
            })
        ];
    }
}
