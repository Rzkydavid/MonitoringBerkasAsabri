<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $nip = $credentials['nip'];
        $password = $credentials['password'];

        // Find user by NIP
        $user = User::where('nip', $nip)->first();

        // If user not found
        if (!$user) {
            return back()->withErrors([
                'nip' => 'NIP tidak ditemukan dalam sistem.',
            ])->withInput($request->only('nip'));
        }

        // If password does not match
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('nip'));
        }

        // If user status is not active
        if (!$user->status) {
            return back()->withErrors([
                'nip' => 'Status user anda sedang tidak aktif, silakan kontak administrator.',
            ])->withInput($request->only('nip'));
        }


        // Attempt authentication (this will create the session)
        if (Auth::attempt(['nip' => $nip, 'password' => $password], $request->boolean('remember'))) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Redirect to intended route or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Fallback (should rarely occur if the above checks passed)
        return back()->withErrors([
            'nip' => 'Gagal melakukan autentikasi. Silakan coba lagi.',
        ])->withInput($request->only('nip'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
