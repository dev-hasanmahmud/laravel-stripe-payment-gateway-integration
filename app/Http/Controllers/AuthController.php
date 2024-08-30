<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempts.
     */
    public function login(Request $request)
    {
        try {
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

                // Check account status
                // $this->checkUserStatus($user);

                return Redirect::intended('dashboard');
            }

            // Increment failed login attempts
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
        } catch (ValidationException $e) {
            // Handle validation exception
            return Redirect::back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Login error: '.$e->getMessage());
            return Redirect::back()->withErrors(['email' => 'An unexpected error occurred.']);
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            Auth::login($user);

            return Redirect::route('dashboard');
        } catch (ValidationException $e) {
            // Handle validation exception
            return Redirect::back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Registration error: '.$e->getMessage());
            return Redirect::back()->withErrors(['email' => 'An unexpected error occurred during registration.']);
        }
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::route('login');
    }

    /**
     * Check account status and handle inactive or expired accounts.
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
