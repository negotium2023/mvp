<?php

namespace App\Http\Controllers\Client\Auth;

use App\ClientPortal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ClientPortalPasswordResetLink;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showRequestPassword()
    {
        return view('auth.portal.client.passwords.email');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $clientPortal = ClientPortal::where('email', $request->email)->first();

        if (!isset($clientPortal)) {
            $status = 'passwords.user';
            return back()->withErrors(['email' => __($status)]);
        }

        $token = $this->generateToken();

        $status = $this->sendResetLink($request->only('email'), $token);

        /*DB::insert('password_resets', [
            'email' => $request->only('email'),
            'token' => $token,
        ]);*/

        //create a new token to be sent to the user. 
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return back()->with(['status' => __($status)]);
    }

    public function sendResetLink($email, $token)
    {
        Mail::to($email)->send(new ClientPortalPasswordResetLink($token));

        return "We have e-mailed your password reset link!";
    }

    private function generateToken()
    {
        // This is set in the .env file
        $key = config('app.key');

        // Illuminate\Support\Str;
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return hash_hmac('sha256', Str::random(40), $key);
    }

}
