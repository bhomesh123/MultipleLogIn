<?php

namespace App\Http\Controllers;

// use Log;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class LoginWithGooglecontroller extends Controller
{
    public function redirectToGoogle()
    {
        
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
    
        try {
            
            $user = Socialite::driver('google')->user();
            Log::info('User Info:', ['user' => $user]);
            $finduser = User::where('google_id', $user->id)->first();
       
            if($finduser){
                Auth::login($finduser);
                return redirect()->intended('dashboard');
       
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
      
                Auth::login($newUser);
      
                return redirect()->intended('dashboard');
            }
      
        } catch (Exception $e) {
            Log::error('Google OAuth Error:', ['error' => $e->getMessage()]);
            dd($e->getMessage());
        }
    }

    public function destroy()
    {
        Auth::destroy();

        // Optionally, you can redirect to a specific page after logout
        return redirect('/login');
    }
}

