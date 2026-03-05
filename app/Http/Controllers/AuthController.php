<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // صفحة تسجيل الدخول
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->is_super_admin
                ? redirect()->route('super_admin.index')
                : redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // تنفيذ تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'phone.required' => 'رقم الهاتف مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->is_super_admin) {
                return redirect()->route('super_admin.index')->with('success', 'مرحباً بك يا مطور!');
            }
            return redirect()->route('dashboard')->with('success', 'مرحباً بك ' . $user->name);
        }

        return back()->withErrors([
            'phone' => 'رقم الهاتف أو كلمة المرور غير صحيحة',
        ])->withInput($request->only('phone'));
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
