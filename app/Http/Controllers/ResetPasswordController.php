<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ResetPasswordController extends Controller
{
    public function sendPasswordReset(Request $request)
    {
        // Check if the email exists in the users table
        $user = User::where('email', $request->email)->first();

        $resetToken = Str::random(60);
        $date = Carbon::now();
        $randomNumber = rand(0,99999999);
        $verifier = $randomNumber.$date;
        if(!$user)
        {
            return redirect()->back()->with('error', 'Failed to reset password.');
        }
        //dd(now());
        if($user)
        {
            try{

                date_default_timezone_set('Asia/Manila');
                $mailData = [
                    'url' => env('APP_URL').'/pasword-reset/verify/'.$resetToken,
                ];
    
                DB::beginTransaction();
                $user->reset_password_token = $resetToken; // 
                $user->reset_expires_at = Carbon::now()->addMinutes(60);;  // debug this..
                $user->save();
                
                Mail::to($user->email)->send(new PasswordResetMail($mailData));
               
    
                DB::commit();
                return redirect()->back()->with('success', 'Please check your email for the password reset link.'.$verifier);
            }
            catch(Exception $e)
            {
                DB::rollback();

                Log::error('Error password reset: '.$e->getMessage());

                return redirect()->back()->with('error', 'Failed to reset Password.');
            }
        }
    }

    public function verifyResetEmail($token)
    {
        $user = User::where('reset_password_token',$token)->where('reset_expires_at','>',now())->first();

        if($user)
        {
           return inertia('Authentication/PasswordReset',[
                'userId' => $user->id,
           ]);
        }
        else
        {
            return redirect()->route('login.show')->with('error','Something went wrong. Please try again.');
        }
    }

    public function resetPassword(Request $request)
    {
        $user = User::findOrFail($request->userId);
        $date = Carbon::now();
        $randomNumber = rand(0,99999999);
        $verifier = $randomNumber.$date;
        if($user)
        {
            try
            {
                DB::beginTransaction();
                $user->password             = Hash::make($request->password);
                $user->reset_password_token = null;
                $user->reset_expires_at     = null;
                $user->save();

                DB::commit();

                return redirect()->route('login.show')->with('success','Successfully reset password.'.$verifier);
            }
            catch(\Exception $e)
            {
                DB::rollback();

                Log::error('Error changing password: '.$e->getMessage());

                return redirect()->back()->with('error', 'Failed to reset password');

            }
            
        }
        else
        {
            dd('wala');
        }
    }
}


