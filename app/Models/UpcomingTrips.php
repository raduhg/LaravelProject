<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UpcomingTrips extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'date',
        'duration',
        'number_of_people',
        'avaliable_spots',
    ];

    public function participates(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'upcoming_trips_participants')->withTimestamps();
    }
}
