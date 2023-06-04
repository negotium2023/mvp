<?php

namespace App\Http\Controllers\Client\Auth;

use App\ClientPortal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm($token)
    {
        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();

        $parameters = [
            'token' => $token,
            'email' => isset($tokenData->email) ? $tokenData->email : ''
        ];

        if(!isset($tokenData->email)){
            return redirect(route('portal.client.login'))->with('error', 'Token expired!');
        }

        return view('auth.portal.client.passwords.reset')->with($parameters);
    }

    public function resetPassword(Request $request)
    {
        $customValidationMessages = [
            'password.regex' => 'Password must contain the following: a number, a upper case character, a lower case character, and a special character',
            'password.min' => 'Password must be at least 10 characters long',
            'password_confirmation.same' => 'Passwords do not match.'
        ];

        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ],$customValidationMessages);
        //some validation
        /*$request->validate([
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8'
        ]);*/

        $token = $request->token;

        $password = $request->password;
        $tokenData = DB::table('password_resets')->where('token', $token)->first();

        $clientPortal = ClientPortal::where('email', $tokenData->email)->first();

        if ( !$clientPortal ){ 
            $status = 'Could not find client';
            return back()->withErrors($status);
        }

        $clientPortal->password = Hash::make($password);
        $clientPortal->update();

        DB::table('password_resets')->where('email', $clientPortal->email)->delete();

        return redirect(route('portal.client.login'))->with('message', 'Password successfully changed!');
    }
}
