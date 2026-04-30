<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('posts')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', '자기 자신은 삭제할 수 없습니다.');
        }

        $user->delete();
        return back()->with('success', '회원이 삭제됐습니다.');
    }
}
