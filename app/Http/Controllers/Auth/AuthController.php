<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register()
    {

      return view('auth.register');
    }

    public function storeUser(RegisterRequest $request)
    {
        $user =  User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));
        // Session::put('user', $user);
        return redirect('home');
    }

    public function login()
    {
      return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
       
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::User();
            Session::put('user', $user);
            return redirect()->intended('home');
        }
        return redirect('login')->with('error', 'Invalid credentials');
    }

    public function logout() {
      Session::flush();
      Auth::logout();
      return redirect('login');
    }

    public function home()
    {
      return view('auth.home');
    }

    public function verifyEmailMessage()
    {
      return (Auth::check()) ?  redirect('/home') : view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        Session::put('user', Auth::User());
        return redirect('/home');
    }
}
