@extends('layouts.app')
@section('title', '내 블로그')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:1.25rem; font-weight:700;">내 글 목록</h2>
    <a href="{{ route('posts.create') }}"
       style="background:#f4a7b9; color:#fff; padding:8px 20px; border-radius:20px;
              text-decoration:none; font-weight:700; font-size:0.85rem;">
        새 글 작성
    </a>
</div>

<div style="background:#fff; border:1px solid #e8e8e8; border-radius:10px; overflow:hidden;">
    @forelse($posts as $post)
    <div style="padding:16px 20px; border-bottom:1px solid #f5f5f5;
                display:flex; justify-content:space-between; align-items:center; gap:16px;">
        <div style="min-width:0;">
            <a href="{{ route('posts.show', $post) }}"
               style="font-weight:600; text-decoration:none; color:#222; font-size:0.95rem;
                      display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ $post->title }}
            </a>
            <div style="font-size:0.78rem; color:#aaa; margin-top:5px; display:flex; gap:10px;">
                @if($post->category)
                <span style="color:#f4a7b9; font-weight:600;">{{ $post->category->name }}</span>
                <span>·</span>
                @endif
                <span>{{ $post->created_at->format('Y.m.d') }}</span>
                <span>·</span>
                <span>조회 {{ number_format($post->views) }}</span>
                @if($post->visibility === 'private')
                <span>·</span>
                <span style="color:#de8a98; font-weight:600;">🔒 비공개</span>
                @endif
            </div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <a href="{{ route('posts.edit', $post) }}"
               style="font-size:0.8rem; padding:5px 14px; border:1px solid #e0e0e0;
                      border-radius:20px; text-decoration:none; color:#555; white-space:nowrap;">
                수정
            </a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST"
                  onsubmit="return confirm('삭제하시겠습니까?')">
                @csrf @method('DELETE')
                <button type="submit"
                        style="font-size:0.8rem; padding:5px 14px; border:1px solid #f5c6c6;
                               border-radius:20px; background:none; color:#c0392b; cursor:pointer; white-space:nowrap;">
                    삭제
                </button>
            </form>
        </div>
    </div>
    @empty
    <div style="padding:50px; text-align:center; color:#bbb;">
        <p>아직 작성한 글이 없습니다.</p>
    </div>
    @endforelse
</div>

@if($posts->hasPages())
<div style="margin-top:24px; display:flex; justify-content:center;">
    {{ $posts->links() }}
</div>
@endif
@endsection