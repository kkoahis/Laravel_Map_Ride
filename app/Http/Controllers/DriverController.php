<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request){
        // return back user and associated driver model
        $user = $request->user();
        // eager load driver model
        $user->load('driver');

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function update(Request $request){
        $request->validate([
            'year' => 'required|numeric|between:2010,2024',
            'model' => 'required|string',
            'color' => 'required|string',
            'license_plate' => 'required|string',
            'name' => 'required|string',
        ]);

        $user = $request->user();

        $user->update($request->only('name'));

        // create or update a driver associated with the user
        $user->driver()->updateOrCreate(
            $request->only(
                'year',
                'model',
                'color',
                'license_plate'
            ));

        $user->load('driver');

        return response()->json([
            'user' => $user
        ], 200);
    }
}
