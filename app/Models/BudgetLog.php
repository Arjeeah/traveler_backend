<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetLog extends Model
{
    protected $fillable = [
        'trip_id',
        'title',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
