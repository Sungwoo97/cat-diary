<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $diaries = Diary::with('user')
            ->latest()
            ->paginate(10);

        $recentUsers = User::withCount('diaries')
            ->orderBy('diaries_count', 'desc')
            ->limit(5)
            ->get()
            ->filter(fn($u) => $u->diaries_count > 0);

        return view('home', compact('diaries', 'recentUsers'));
    }
}