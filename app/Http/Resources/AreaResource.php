<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city_id' => $this->city_id,
            'description' => $this->description,
            'is_recommended' => $this->is_recommended,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // Pivot data (when loaded through relationship)
            'pivot' => $this->whenPivotLoaded('trip_areas', function () {
                return [
                    'trip_id' => $this->pivot->trip_id,
                    'area_id' => $this->pivot->area_id,
                    'order' => $this->pivot->order,
                    'created_at' => $this->pivot->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $this->pivot->updated_at->format('Y-m-d H:i:s'),
                ];
            }),

            // Relationships
            'city' => new CityResource($this->whenLoaded('city')),
            'trips' => TripResource::collection($this->whenLoaded('trips')),
        ];
    }
}