@extends('layouts.app')
@section('title', $category->name . ' - 카테고리')

@section('content')
<div style="margin-bottom:20px;">
    <p style="font-size:0.78rem; color:#aaa; margin-bottom:4px;">카테고리</p>
    <h2 style="font-size:1.25rem; font-weight:700;">{{ $category->name }}</h2>
</div>

<div style="display:flex; flex-direction:column; gap:16px;">
    @forelse($posts as $post)
    <article style="background:#fff; border:1px solid #e8e8e8; border-radius:10px; padding:20px 24px;">
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
    <p style="color:#bbb; text-align:center; padding:50px;">이 카테고리에 글이 없습니다.</p>
    @endforelse
</div>

@if($posts->hasPages())
<div style="margin-top:24px; display:flex; justify-content:center;">
    {{ $posts->links() }}
</div>
@endif
@endsection