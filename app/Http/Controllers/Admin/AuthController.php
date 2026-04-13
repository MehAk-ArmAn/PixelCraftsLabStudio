<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = strtolower(trim($request->email));
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found: ' . $email,
            ])->onlyInput('email');
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'email' => 'Password check failed.',
            ])->onlyInput('email');
        }

        if ($user->role !== 'admin') {
            return back()->withErrors([
                'email' => 'This user is not an admin.',
            ])->onlyInput('email');
        }

        if (!Auth::attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            return back()->withErrors([
                'email' => 'Auth::attempt failed after password match.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
