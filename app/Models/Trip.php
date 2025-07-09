<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'user_id',
        'city_id',
        'title',
        'description',
        'budget',
        'start_date',
        'end_date',
        'number_of_people',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'trip_areas')
            ->using(TripArea::class)
            ->withPivot('order')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function budgetLogs()
    {
        return $this->hasMany(BudgetLog::class);
    }
}