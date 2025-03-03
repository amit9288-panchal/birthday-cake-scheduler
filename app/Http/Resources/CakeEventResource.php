<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CakeEventResource extends ResourceCollection
{
    public function toArray($request): array
    {
        $response = [];
        foreach ($this->resource as $cakeEvent) {
            $eventDevelopers = $cakeEvent->cakeEventDeveloper()->get();
            $developersList = [];
            foreach ($eventDevelopers as $developer)
                $developersList[] = $developer->developer()->first()->name;

            $response[] = [
                'id' => $cakeEvent->id,
                'cake_date' => Carbon::parse($cakeEvent->cake_date)->format('d-m-Y'),
                'small_cakes' => $cakeEvent->small_cakes,
                'large_cakes' => $cakeEvent->large_cakes,
                'developers' => $developersList,
                'created_at' => Carbon::parse($cakeEvent->created_at)->format('d-m-Y-H-i-s'),
            ];
        }
        return $response;
    }
}
