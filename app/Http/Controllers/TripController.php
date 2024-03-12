<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        return $request->user()->trips()->create(
            $request->only(
                'origin',
                'destination',
                'destination_name'
            )
        );
    }

    public function show(Request $request, Trip $trip)
    {
        // is the trip associated with the user?
        if ($trip->user->id === $request->user()->id) {
            return $trip;
        }
        if ($trip->driver && $request->user()->driver) {
            if ($trip->driver->id === $request->user()->driver->id) {
                return $trip;
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function accept(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_location' => 'required|string',
        ]);

        // a driver can only accept a trip if they are not the user
        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request->driver_location,
        ]);

        $trip->load('driver.user');

        return $trip;
    }

    public function start(Request $request, Trip $trip)
    {
        // a driver has stated taking a passenger to their destination
        $trip->update([
            'is_started' => true
        ]);

        $trip->load('driver.user');

        return $trip;
    }

    public function end(Request $request, Trip $trip)
    {
        // a driver ends a trip 
        $trip->update([
            'is_completed' => true
        ]);

        $trip->load('driver.user');

        return $trip;
    }

    public function location(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_location' => 'required|string',
        ]);

        // update the driver's location
        $trip->update([
            'driver_location' => $request->driver_location
        ]);

        $trip->load('driver.user');

        return $trip;
    }
}
