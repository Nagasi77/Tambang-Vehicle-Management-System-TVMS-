<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        // Check if account is locked
        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            return back()->withErrors([
                'email' => 'Akun Anda terkunci. Coba lagi setelah ' . $user->locked_until->format('H:i'),
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until'          => null,
            ]);

            return $this->redirectByRole($user);
        }

        // Failed attempt — increment counter if user exists
        if ($user) {
            $user->increment('failed_login_attempts');

            if ($user->failed_login_attempts >= 5) {
                $user->update(['locked_until' => now()->addMinutes(15)]);

                return back()->withErrors([
                    'email' => 'Akun Anda terkunci selama 15 menit karena terlalu banyak percobaan login yang gagal.',
                ]);
            }
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    /**
     * Log the user out and invalidate the session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect the user based on their role.
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended('/approvals');
    }
}
