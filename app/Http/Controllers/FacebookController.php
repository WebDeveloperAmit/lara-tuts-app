<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
        
            $user = Socialite::driver('facebook')->user();

            echo "<pre>";
            print_r($user);
            echo "</pre>";
            echo "<hr/>";

         
            $findUser = User::where('facebook_id', $user->id)->first();

            echo "<pre>";
            print_r($findUser);
            echo "</pre>";
            echo "<hr/>";

            die('stop');
         
            if($findUser){
         
                Auth::login($findUser);
        
                return redirect()->intended('home');
         
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'facebook_id'=> $user->id,
                        'password' => encrypt('123456dummy')
                    ]);
        
                Auth::login($newUser);
        
                return redirect()->intended('home');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}