<?php

namespace App\Http\Controllers;

use App\Models\UpcomingTrips;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UpcomingTripsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'difficulty' => 'required|string',
            'date' => 'required|date',
            'duration' => 'required|string',
            'number_of_people' => 'required|integer',
            'avaliable_spots' => 'required|integer',
        ]);

        $upcomingTrip = UpcomingTrips::create($validatedData);

        return redirect('/dashboard')->with('success', 'Trip created successfully!');
    }

    public function index(): View
    {
        $upcomingTrips = UpcomingTrips::all();

        return view('dashboard', [
            'upcomingTrips' => $upcomingTrips,
        ]);
    }

    public function destroy(UpcomingTrips $trip): RedirectResponse
    {
        $trip->delete();
 
        return redirect(route('upcoming-trips.index'))->with('success', 'Trip deleted!');
    }

}
