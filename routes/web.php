<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPostController;

// ── 메인 피드 (비로그인도 접근 가능) ─────────────────────────
Route::get('/', [PostController::class, 'index'])->name('home');

// ── 글 상세 (비로그인도 접근 가능) ───────────────────────────
Route::get('/post/{post}', [PostController::class, 'show'])->name('posts.show');

// ── AJAX: 조회수 증가 (비로그인 포함 모든 방문자 카운트) ──────
Route::post('/post/{post}/views', [PostController::class, 'incrementViews'])->name('posts.views');

// ── 카테고리 / 태그 목록 (비로그인도 접근 가능) ───────────────
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tag/{slug}',      [TagController::class, 'show'])->name('tags.show');

// ── 인증 (로그인 안 한 사람만 접근 가능) ──────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 로그아웃
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── 관리자 (로그인 + is_admin 필요) ───────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                          [AdminController::class,         'dashboard'])->name('dashboard');

    Route::get('/categories',                [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',               [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}',     [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',  [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/users',                     [AdminUserController::class,     'index'])->name('users.index');
    Route::delete('/users/{user}',           [AdminUserController::class,     'destroy'])->name('users.destroy');

    Route::get('/posts',                     [AdminPostController::class,     'index'])->name('posts.index');
    Route::delete('/posts/{post}',           [AdminPostController::class,     'destroy'])->name('posts.destroy');
});

// ── 글 작성/수정/삭제/내 글 목록 (로그인 필요) ────────────────
Route::middleware('auth')->group(function () {
    Route::get('/write',            [PostController::class, 'create'])->name('posts.create');
    Route::post('/write',           [PostController::class, 'store'])->name('posts.store');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/post/{post}',      [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}',   [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/my',               [PostController::class, 'my'])->name('posts.my');
});
