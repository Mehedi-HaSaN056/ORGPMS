<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Rate limiting — max 5 attempts per minute
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            // Check account status
            if ($user->status !== 'active') {
                Auth::logout();
                $this->logLogin($request, $user, false, 'Account suspended or inactive');
                return back()->withErrors(['email' => 'Your account is suspended. Contact admin.']);
            }

            // Update last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $this->logLogin($request, $user, true);

            return redirect()->intended($this->redirectTo($user));
        }

        RateLimiter::hit($key, 60);

        // Log failed attempt
        $failUser = User::where('email', $request->email)->first();
        if ($failUser) {
            $this->logLogin($request, $failUser, false, 'Invalid password');
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.'])
                     ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Update logout time in log
        $latestLog = LoginLog::where('user_id', Auth::id())
            ->whereNull('logged_out_at')
            ->latest()
            ->first();

        if ($latestLog) {
            $latestLog->update(['logged_out_at' => now()]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = \Password::sendResetLink($request->only('email'));

        return $status === \Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link sent to your email.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $status = \Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status === \Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => __($status)]);
    }

    // ─── Redirect based on role ────────────────────────────────

    private function redirectTo(User $user): string
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return route('admin.dashboard');
        }
        if ($user->hasRole('management')) {
            return route('management.dashboard');
        }
        return route('dashboard');
    }

    // ─── Log login activity ────────────────────────────────────

    private function logLogin(Request $request, User $user, bool $success, ?string $reason = null): void
    {
        LoginLog::create([
            'user_id'        => $user->id,
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
            'browser'        => $request->userAgent(),
            'platform'       => 'unknown',
            'device'         => 'unknown',
            'is_successful'  => $success,
            'failure_reason' => $reason,
            'logged_in_at'   => now(),
        ]);
    }
}