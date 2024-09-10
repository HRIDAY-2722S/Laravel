<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\forgotpassword;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\Redirect;
use Nette\Utils\Random;

class LoginController extends Controller
{
    public function login(){
        return view('login');
    }

    public function userlogin(Request $request)
    {
        if ($request->has('guest_mode_enabled')) {

            $this->simulateGuestSession($request);
            return redirect()->route('usersdashboard');
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.email' => 'Kindly input a correct email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    
                    
                    $request->session()->put('email', $user->email);
                    $request->session()->put('id', $user->id);
                    $request->session()->put('name', $user->name);
                    $request->session()->put('role', $user->role);
                    return redirect()->route('usersdashboard');
                } else {
                    return redirect()->back()->withErrors(['password' => 'Incorrect password'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['email' => 'Your account is currently inactive. Please get in touch with the administrator to activate it.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'We were unable to find any user linked to this email address.'])->withInput();
        }
    }

    private function simulateGuestSession(Request $request)
    {
        $request->session()->put('email', 'guest@example.com');
        $request->session()->put('id', '0'); 
        $request->session()->put('name', 'Guest');
        $request->session()->put('role', 'guest');
    }


    public function register(){
        return view('register');
    }

    public function userregister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^[0-9]+$/|digits:10',
            'otp' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'profile_image' => 'required',
        ], [
            'email.email' => 'Kindly input a correct email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.digits' => 'Kindly input a 10 digit phone number.',
            'phone.regex' => 'A phone number should only consist of numbers.',
            'otp.required' => 'One Time Password(OTP) is required',
            'password.confirmed' => 'Password and confirm password does not match. Please try again.',
            'profile_image.required' => 'Kindly select a valid image file.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $Otp = Cache::get('otp_' . $request->mobile);
            if($request->otp == $Otp){
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->remember_token = Str::random(40);
                $user->password = bcrypt($request->password);
                if ($request->profile_image!= " ") {
                    // here we will store image
                    $profile_image = $request->profile_image;
                    $ext = $profile_image->getClientOriginalExtension();
                    $mimeType = $profile_image->getClientMimeType();
                    $imageName = time().'.'.$ext;
                
                    if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                        $profile_image->move(public_path('profile_picture'), $imageName);
                        
                        $user->profile_picture = $imageName;
                        $user->save();
                    }else{
                        return redirect()->back()->withErrors(['profile_image' => 'Only JPEG, PNG, and GIF images are allowed.'])->withInput();
                    }
                }
        
                // $user->save();
                return redirect()->route('login')->with('success', 'You have successfully registered. Please login');
            }else{
                return redirect()->back()->withErrors(['otp' => 'The OTP does not match. Please try again with the correct OTP that was sent to your mobile number.'])->withInput();
            }
        }
    }

    public function logout(){
        session::flush();
        return redirect()->route('login');
    }


    public function forgotpassword(){
        return view('forgotpassword');
    }

    public function forgot_password(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = Str::random(40);
                $user->remember_token = $token;
                $user->save();
                Mail::to($user->email)->send(new forgotpassword($user));
                return redirect()->route('login')->with('success', 'We have sent a link to your email to reset your password. Please check your email.');
            }else{
                return redirect()->back()->withErrors(['email' => 'Email not found.'])->withInput();
            }
        }
    }

    public function resetpassword($token){
        $user = User::where('remember_token', '=', $token)->first();
        if ($user) {
            return view('resetpassword', compact('user'));
        }else{
            return redirect()->route('login')->withErrors(['token' => 'Invalid token.'])->withInput();
        }      
    }


    public function reset_password(Request $request, $token){
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::where('remember_token', $token)->first();
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random(40);
                $user->save();
                return redirect()->route('login')->with('success', 'Password has been changed successfully. Please login with your new password.');
            }else{
                return redirect()->back()->withErrors(['email' => 'Invalid token.'])->withInput();
            }
        }
                            
    }

    public function generate_otp(Request $request)
    {

        $otp = rand(100000, 999999);

        Cache::put('otp_' . $request->mobile, $otp, 300);

        $brandName = 'YourBrandName';

        $basic  = new \Vonage\Client\Credentials\Basic("b50d35fb", "b3SvHLcU3ylqzSuz");
        $client = new \Vonage\Client($basic);

        $messageText = "Your mobile verification OTP is $otp. This OTP is valid for the next 5 minutes.";

        try {
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS("918235708100", $brandName, $messageText)
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'The message was sent successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The message failed with status: ' . $message->getStatus(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
