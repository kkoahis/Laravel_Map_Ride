<?php

namespace App\Http\Controllers;

use App\Notifications\LoginNeedsVerfication;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request){
        // validate phone number
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);
        
        // find or create a user model
        $user = \App\Models\User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if(!$user){
            return response()->json([
                'message' => 'Could not process the use with the given phone number. Please try again.'
            ], 401);
        }

        // send the user a one-time use code
        $user->notify(new LoginNeedsVerfication());

        // return a response
        return response()->json([
            'message' => 'A verification code has been sent to your phone number.'
        ], 200);
    }

    public function verify(Request $request){
        // validate the request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        // find the user
        $user = \App\Models\User::where('phone', $request->phone)->where('login_code', $request->login_code)->first();

        // is the code provided the same as the one in the database?   
        // if yes, return a auth token
        if($user){
            $user->update(['login_code' => null]);
            
            return $user->createToken($request->login_code)->plainTextToken;
        }

        // if no, return an error message
        return response()->json([
            'message' => 'Could not verify the user with the given phone number and login code. Please try again.'
        ], 401);
    }
}
