<?php

namespace App\Http\Controllers\Client\Auth;

use App\Client;
use App\ClientPortal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.portal.client.login',[
            'title' => 'Client Login',
            'loginRoute' => 'portal.client.login',
            'forgotPasswordRoute' => 'portal.client.password.request',
        ]);
    }

    /**
     * Login the admin.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        //Validation...
        $this->validator($request);

        $clientPortal = ClientPortal::where('email', $request->email)->where('id_number', $request->id_number)->first();
        if(!isset($clientPortal)){
            $client = Client::where('email', $request->email)->where('id_number', $request->id_number)->first();
            if(isset($client->id)){
                $clientPortal = new ClientPortal();
                $clientPortal->email = $client->email;
                $clientPortal->id_number = $client->id_number;
                $clientPortal->client_id = $client->id;
                $clientPortal->created_at = now();
                $clientPortal->save();

                return redirect()
                        ->back()
                        ->withInput()
                        ->with('error','Login failed, It seems this is the first time you are trying to login, please click on reset your password to create a new password and login!');
            }
        }

        if(Auth::guard('clients')->attempt($request->only('email','password', 'id_number'), $request->filled('remember'))){
            //Authentication passed...
            return redirect(route('portal.client'))->with('message','You are Logged in as a Client!');
        }

        //Authentication failed...
        return $this->loginFailed();
    }

    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('clients')->logout();

        // $request->session()->invalidate();

        return redirect()
            ->route('portal.client.login')
            ->with('status','Client has been logged out!');

        Auth::logout();
        return redirect('portal.client.logout');
    }

    /**
     * Validate the form data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            // 'email'    => 'required|email|exists:client_portals|min:5|max:191',
            'email'    => 'required|email|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
            'id_number' => 'required|numeric|min:13',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }

    /**
     * Redirect back after a failed login.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return redirect()
        ->back()
        ->withInput()
        ->with('error','Login failed, please try again!');
    }
}
