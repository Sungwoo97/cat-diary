<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 로그인 폼
    public function loginForm()
    {
        return view('auth.login');
    }

    // 로그인 처리
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('home')
                ->with('success', '환영해요! 🐱');
        }

        return back()->withErrors([
            'email' => '이메일 또는 비밀번호가 올바르지 않아요.',
        ])->withInput();
    }

    // 회원가입 폼
    public function registerForm()
    {
        return view('auth.register');
    }

    // 회원가입 처리
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', '가입 완료! 묘생일기를 시작해봐요 🐾');
    }

    // 로그아웃
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}