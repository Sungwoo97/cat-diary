<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiaryController;

// 메인
Route::get('/', function () {
    return view('home');
})->name('home');

// 인증
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// 다이어리 - 로그인 필요
Route::middleware('auth')->group(function () {
    Route::resource('diary', DiaryController::class);
    Route::post('/diary/{diary}/like', [DiaryController::class, 'like'])->name('diary.like');
});
// 메인 - 기존 클로저 방식에서 Controller 방식으로 변경
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');