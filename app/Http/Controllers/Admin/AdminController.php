<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'   => User::count(),
            'total_posts'   => Post::count(),
            'public_posts'  => Post::where('visibility', 'public')->count(),
            'total_views'   => Post::sum('views'),
            'total_categories' => Category::count(),
        ];

        $recent_users = User::latest()->limit(5)->get();
        $recent_posts = Post::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_posts'));
    }
}
