<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $findUser = User::where('google_id', $user->id)->first();
            if($findUser) {
                Auth::login($findUser);
                return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));
            }else{
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => Hash::make(Str::random(32))
                    ]);
                Auth::login($newUser);
                return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));
            }
        
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}