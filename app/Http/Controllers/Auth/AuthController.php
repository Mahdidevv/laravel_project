<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OTPSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('auth.login');
        }
        $request->validate([
            'cellphone' => 'required'
        ]);
        try {
            $user = User::where('cellphone', $request->cellphone)->first();
            $OTPNumber = rand(100000, 999999);
            $login_token = Hash::make(Carbon::now());
            if ($user) {
                $user->update([
                    'otp' => $OTPNumber,
                    'login_token' => $login_token
                ]);
            } else {
                $user = User::create([
                    'cellphone' => $request->cellphone,
                    'otp' => $OTPNumber,
                    'login_token' => $login_token
                ]);
            }
            $user->notify(new OTPSms($OTPNumber));
            return response(['login_token' => $login_token], 200);
        } catch (\Exception $ex) {
            return response(['error' => $ex], 422);
        }

    }

    public function checkOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'login_token' => 'required'
        ]);
        try {
            $user = User::where('login_token', $request->login_token)->first();
            if ($user->otp == $request->otp) {
                auth()->login($user);
                return response(['success' => 'کاربر عزیز خوش آموید'], 200);
            } else {
                return response(['error' => 'کد تاییدیه صحیح نیست'], 200);
            }
        } catch (\Exception $ex) {
            return response(['error' => $ex], 422);
        }
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'login_token' => 'required'
        ]);
        try {
            $user = User::where('login_token', $request->login_token)->first();
            $OTPNumber = rand(100000, 999999);
            $login_token = Hash::make(Carbon::now());
            $user->update([
                'otp' => $OTPNumber,
                'login_token' => $login_token
            ]);
            $user->notify(new OTPSms($OTPNumber));
            return response(['login_token' => $login_token], 200);
        } catch (\Exception $ex) {
            return response(['error' => $ex], 422);
        }
    }
}
