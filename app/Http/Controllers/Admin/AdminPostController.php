<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])->latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();
        Cache::increment('home_feed_version');

        return back()->with('success', '게시글이 삭제됐습니다.');
    }
}
