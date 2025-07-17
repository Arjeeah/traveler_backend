<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'city_id' => $this->city_id,
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->budget,
            'attendance' => $this->number_of_people,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'number_of_people' => $this->number_of_people,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'city' => new CityResource($this->whenLoaded('city')),
            'areas' => AreaResource::collection($this->whenLoaded('areas')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'budget_logs' => BudgetLogResource::collection($this->whenLoaded('budgetLogs')),
        ];
    }
}
