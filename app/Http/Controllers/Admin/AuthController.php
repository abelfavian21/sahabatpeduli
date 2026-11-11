<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('admin.login');
    }

    public function login(Request $r)
    {
        $cred = $r->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($cred, $r->boolean('remember')) && Auth::user()->is_admin) {
            $r->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }
        Auth::logout();

        return back()->withErrors(['email' => 'Email atau password salah, atau akun bukan admin.'])->onlyInput('email');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }
}
