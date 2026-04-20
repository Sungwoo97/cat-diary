<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiaryController extends Controller
{
    // 목록
    public function index()
    {
        $diaries = Diary::with('user')
            ->latest()
            ->paginate(12);

        return view('diary.index', compact('diaries'));
    }

    // 작성 폼
    public function create()
    {
        return view('diary.create');
    }

    // 저장
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|max:255',
            'content'    => 'required',
            'diary_date' => 'required|date',
            'photo'      => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('diaries', 'public');
        }

        Diary::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'content'    => $request->content,
            'mood'       => $request->mood,
            'weather'    => $request->weather,
            'location'   => $request->location,
            'photo'      => $photoPath,
            'diary_date' => $request->diary_date,
        ]);

        return redirect()->route('diary.index')
            ->with('success', '일기가 저장됐어요! 🐱');
    }

    // 상세
    public function show(Diary $diary)
    {
        return view('diary.show', compact('diary'));
    }

    // 수정 폼
    public function edit(Diary $diary)
    {
        abort_if($diary->user_id !== Auth::id(), 403);
        return view('diary.create', compact('diary'));
    }

    // 수정 저장
    public function update(Request $request, Diary $diary)
    {
        abort_if($diary->user_id !== Auth::id(), 403);

        $request->validate([
            'title'      => 'required|max:255',
            'content'    => 'required',
            'diary_date' => 'required|date',
            'photo'      => 'nullable|image|max:5120',
        ]);

        $photoPath = $diary->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $request->file('photo')->store('diaries', 'public');
        }

        $diary->update([
            'title'      => $request->title,
            'content'    => $request->content,
            'mood'       => $request->mood,
            'weather'    => $request->weather,
            'location'   => $request->location,
            'photo'      => $photoPath,
            'diary_date' => $request->diary_date,
        ]);

        return redirect()->route('diary.index')
            ->with('success', '일기가 수정됐어요! ✏️');
    }

    // 삭제
    public function destroy(Diary $diary)
    {
        abort_if($diary->user_id !== Auth::id(), 403);

        if ($diary->photo) {
            Storage::disk('public')->delete($diary->photo);
        }

        $diary->delete();

        return redirect()->route('diary.index')
            ->with('success', '일기가 삭제됐어요! 🗑️');
    }

    // 좋아요
    public function like(Diary $diary)
    {
        $diary->increment('likes');
        return response()->json(['likes' => $diary->likes]);
    }
}