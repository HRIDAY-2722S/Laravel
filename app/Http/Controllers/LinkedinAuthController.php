<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LinkedinAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callbackLinkedin()
    {
        try {
            $linked_user = Socialite::driver('linkedin')->user();

            if (!$linked_user) {
                throw new \Exception('LinkedIn user not found.');
            }

            $user = User::where('email', $linked_user->getEmail())->first();

            if (!$user) {
                $user = new User;
                $user->name = $linked_user->getName();
                $user->email = $linked_user->getEmail();
                $user->password = bcrypt('123');
                $user->remember_token = Str::random(60);
                $user->linkedin_id = $linked_user->getId(); 
                $user->save();
            

                session()->put('email', $linked_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $linked_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');

            
            }else{


                session()->put('email', $linked_user->getEmail());
                session()->put('id', $user->id);
                session()->put('name', $linked_user->getName());
                session()->put('role', 'user');

                return redirect()->route('usersdashboard');


            }

        } catch (\Throwable $th) {
            dd('Something went wrong', $th->getMessage());
        }
    }
}

?>