<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /**
     * For show login page
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * For login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Reset failed login attempts
            $user->failed_login_attempts = 0;
            $user->save();

            // Check account active and expires
            $this->checkUserStatus($user);

            return Redirect::intended('dashboard');
        }

        // Custom logic for failed login attempts
        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            $user->failed_login_attempts++;
            $user->last_failed_login = now();
            $user->save();

            if ($user->failed_login_attempts > 2) {
                $lastAttempt = $user->last_failed_login;
                $lockoutDuration = now()->diffInMinutes($lastAttempt);

                if ($lockoutDuration < 5) {
                    return Redirect::back()->withErrors(['email' => 'Too many failed attempts. Try again after 5 minutes.']);
                }
            }
        }

        return Redirect::back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * For show register form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * For Register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'account_active' => true,
            'account_expires' => now()->addMonth(),
        ]);

        Auth::login($user);

        return Redirect::route('dashboard');
    }

    /**
     * For logout
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::route('login');
    }

    /**
     * For check account active and expires
     */
    protected function checkUserStatus($user)
    {
        if (!$user->account_active) {
            Auth::logout();

            return Redirect::route('login')->withErrors(['account' => 'Account is not active']);
        }

        if (now()->greaterThan($user->account_expires)) {
            $user->account_active = false;
            $user->save();

            Auth::logout();

            return Redirect::route('login')->withErrors(['account' => 'Account has expired']);
        }
    }
}
