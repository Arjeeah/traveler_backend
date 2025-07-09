<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name',
        'city_id',
        'description',
        'is_recommended',
    ];

    protected function casts(): array
    {
        return [
            'is_recommended' => 'boolean',
        ];
    }

    // Relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_areas')
            ->using(TripArea::class)
            ->withPivot('order')
            ->withTimestamps();
    }
}