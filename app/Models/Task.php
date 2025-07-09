<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'trip_id',
        'title',
        'is_done',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'is_done' => 'boolean',
        ];
    }

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
