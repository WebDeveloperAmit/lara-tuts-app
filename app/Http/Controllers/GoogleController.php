<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        // Logic to redirect to Google OAuth
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
        
            $user = Socialite::driver('google')->user();
            // echo "<pre>";
            // print_r($user);
            // echo "</pre>";
            // echo "<hr>";
            $findUser = User::where('google_id', $user->id)->first();

            // echo "<pre>";
            // print_r($findUser);
            // echo "</pre>";
            // echo "<hr>";

            // die();
         
            if($findUser) {
                Auth::login($findUser);
                return redirect()->intended('checkout');
            }else{
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => encrypt('123456dummy')
                    ]);
         
                Auth::login($newUser);
        
                return redirect()->intended('checkout');
            }
        
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}