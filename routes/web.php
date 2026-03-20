<?php
use Illuminate\Support\Facades\Route;

Route::get('/',               fn() => view('home'))->name('home');
Route::get('/login',          fn() => view('auth.login'))->name('login');
Route::get('/register',       fn() => view('auth.register'))->name('register');
Route::get('/diary',          fn() => view('diary.index'))->name('diary.index');
Route::get('/diary/create',   fn() => view('diary.create'))->name('diary.create');
Route::get('/diary/{id}/edit',fn($id) => view('diary.create'))->name('diary.edit');
