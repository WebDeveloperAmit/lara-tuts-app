<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $fbUser = Socialite::driver('facebook')->user();
            // Email may be NULL
            $email = $fbUser->getEmail();

            // Create fallback email if not provided
            if (!$email) {
                $email = 'fb_' . $fbUser->getId() . '@facebook.com';
            }

            // Find user by facebook_id
            $user = User::where('facebook_id', $fbUser->getId())->first();
            if ($user) {
                Auth::login($user);
                return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));
            }
            // Create new user
            $user = User::create([
                'name'        => $fbUser->getName() ?? 'Facebook User',
                'email'       => $email,
                'facebook_id' => $fbUser->getId(),
                'password'    => Hash::make(Str::random(32)), // secure
            ]);
            Auth::login($user);
            return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));

        } catch (\Exception $e) {
            Log::error('Facebook login error: ' . $e->getMessage());
            flash()->error($e->getMessage());
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('error', 'Facebook login failed.');
        }
    }
}