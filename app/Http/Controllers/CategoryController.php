<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    // 특정 카테고리에 속한 공개 글 목록
    public function show(string $slug)
    {
        // slug로 카테고리 찾기 (없으면 404)
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()
            ->with(['user', 'tags'])
            ->where('visibility', 'public')
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'posts'));
    }
}
