<?php

namespace App\Http\Controllers;

use App\Models\UpcomingTrips;
use Illuminate\Http\Request;

class UpcomingTripsParticipantsController extends Controller
{
    public function joinTrip(UpcomingTrips $trip)
    {
        if($trip->avaliable_spots < 1) {
            return redirect()->route('upcoming-trips.index')->with('error', 'No more spots avaliable on this trip.');
        }
       $takes_part = auth()->user();
       $takes_part->participates()->attach($trip);

       $trip->decrement('avaliable_spots');
        return redirect()->route('upcoming-trips.index')->with('success', 'You have successfully joined the trip.');
    }

    public function leaveTrip(UpcomingTrips $trip)
    {
       $takes_part = auth()->user();
       $takes_part->participates()->detach($trip);
       $trip->increment('avaliable_spots');
        return redirect()->route('upcoming-trips.index')->with('error', 'You left the trip.');
    }
}

