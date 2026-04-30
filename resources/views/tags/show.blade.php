@extends('layouts.app')
@section('title', '#' . $tag->name . ' - 태그')

@section('content')
<div style="margin-bottom:20px;">
    <p style="font-size:0.78rem; color:#aaa; margin-bottom:4px;">태그</p>
    <h2 style="font-size:1.25rem; font-weight:700;">#{{ $tag->name }}</h2>
</div>

<div style="display:flex; flex-direction:column; gap:16px;">
    @forelse($posts as $post)
    <article style="background:#fff; border:1px solid #e8e8e8; border-radius:10px; padding:20px 24px;">
        {{-- 카테고리가 있으면 표시 --}}
        @if($post->category)
        <a href="{{ route('categories.show', $post->category->slug) }}"
           style="font-size:0.75rem; color:#f4a7b9; font-weight:700; text-decoration:none; display:block; margin-bottom:5px;">
            {{ $post->category->name }}
        </a>
        @endif
        <h3 style="margin-bottom:8px;">
            <a href="{{ route('posts.show', $post) }}"
               style="text-decoration:none; color:#222; font-size:1rem; font-weight:700; line-height:1.4;">
                {{ $post->title }}
            </a>
        </h3>
        <p style="color:#777; font-size:0.875rem; line-height:1.65; margin-bottom:12px;">
            {{ Str::limit(strip_tags($post->content), 100) }}
        </p>
        <div style="font-size:0.78rem; color:#aaa; display:flex; gap:10px;">
            <span>{{ $post->user->name }}</span>
            <span>·</span>
            <span>{{ $post->created_at->format('Y.m.d') }}</span>
        </div>
    </article>
    @empty
    <p style="color:#bbb; text-align:center; padding:50px;">이 태그를 가진 글이 없습니다.</p>
    @endforelse
</div>

@if($posts->hasPages())
<div style="margin-top:24px; display:flex; justify-content:center;">
    {{ $posts->links() }}
</div>
@endif
@endsection