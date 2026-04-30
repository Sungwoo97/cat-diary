<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->with('user')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:50',
            'user_id' => 'required|exists:users,id',
        ]);

        Category::create([
            'user_id' => $request->user_id,
            'name'    => $request->name,
            'slug'    => Str::slug($request->name),
        ]);

        return back()->with('success', '카테고리가 추가됐습니다.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|max:50']);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', '카테고리가 수정됐습니다.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', '카테고리가 삭제됐습니다.');
    }
}
