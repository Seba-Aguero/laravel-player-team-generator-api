<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlayerCollectionResource extends ResourceCollection
{
    public static $wrap = null;

    public function toArray($request)
    {
        return $this->collection->map(function ($player) {
            return new PlayerResource($player);
        });
    }
}