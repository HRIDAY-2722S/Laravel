<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect(){

        return Socialite::driver('facebook')->redirect();

    }

    public function callbackFacebook(){

        try{
            $facebook_user = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebook_user->getEmail())->first();


            if(!$user){
                $user = new User;

                $user->name = $facebook_user->getName();
                $user->email = $facebook_user->getEmail();
                $user->password = bcrypt('123');
                $user->remember_token = Str::random(60);
                $user->facebook_id = $facebook_user->getId();
                $user->save();

                session()->put('email', $facebook_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $facebook_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');

            }else{

                session()->put('email', $facebook_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $facebook_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');

            }

        }catch(\Throwable $th){
            dd('Something went wrong', $th->getMessage());
        }

    }
}
