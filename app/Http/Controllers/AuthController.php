<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        $pageTitle = 'Sign In';
        return view('auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if (RateLimiter::tooManyAttempts($request->ip(), 10)) {
            $seconds = RateLimiter::availableIn($request->ip(), 10);

            throw ValidationException::withMessages(['login_failed' => "Too many login attempts. Please try again in $seconds seconds."]);
        }

        // Get user by email

        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->aktif != 'Y') {
                    throw ValidationException::withMessages(['login_failed' => 'Akun anda tidak aktif, Silahkan hubungi administrator']);
                }

                if (!$user || !Hash::check($request->password, $user->password)) {
                    RateLimiter::hit($request->ip());

                    // Set validation error to be viewed in blade
                    throw ValidationException::withMessages(['login_failed' => 'Password anda salah silahkan coba lagi']);
                }

                RateLimiter::clear($request->ip());
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                throw ValidationException::withMessages(['login_failed' => 'Akun belum terdaftar, Silahkan hubungi administrator']);
            }
        } catch (\Exception $err) {
            throw ValidationException::withMessages(['login_failed' => $err->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
