<?php

namespace App\Http\Controllers;

// use Illuminate\Container\Attributes\Auth; ganti ini menjadi yang dibawah ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginBackend()
    {
        return view('backend.v-login.login', [
            'judul' => 'Login'
        ]);
    }

    public function authbackend(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::User()->status == 0) {
                Auth::logout();
                return back()->with('error', 'User Belum Aktif');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('backend.beranda'));
        }
        return back()->with('error', 'Login Gagal');
    }

    public function logoutBackend()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('backend.login'));
    }
}
