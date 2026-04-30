<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    // 특정 태그가 붙은 공개 글 목록
    public function show(string $slug)
    {
        // slug로 태그 찾기 (없으면 404)
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['user', 'category'])
            ->where('visibility', 'public')
            ->latest()
            ->paginate(12);

        return view('tags.show', compact('tag', 'posts'));
    }
}