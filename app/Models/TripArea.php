<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TripArea extends Pivot
{
    protected $fillable = [
        'trip_id',
        'area_id',
        'order',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}