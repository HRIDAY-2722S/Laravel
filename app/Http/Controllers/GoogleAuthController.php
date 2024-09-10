<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){

        try{
            $google_user = Socialite::driver('google')->user();

            $user = User::where('email',$google_user->email)->first();

            if(!$user){

                $user = new User;
                $user->name = $google_user->getName();
                $user->email = $google_user->getEmail();
                $user->password = bcrypt('123');
                $user->remember_token = Str::random(60);
                $user->google_id = $google_user->getId();
                $user->save();

                session()->put('email', $google_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $google_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');


            }else{
                
                session()->put('email', $google_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $google_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');
            }

        }catch(\Throwable $th){
            dd('Something went wrong', $th->getMessage());
        }

    }
}
