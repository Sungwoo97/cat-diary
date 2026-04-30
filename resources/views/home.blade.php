@extends('layouts.app')
@section('title', 'Cat-Log')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 1.25rem; font-weight: 700; color: #333;">전체 글</h2>
</div>

{{-- 글 카드 목록 --}}
<div style="display: flex; flex-direction: column; gap: 16px;">
    @forelse($posts as $post)
    <article style="background:#fff; border:1px solid #e8e8e8; border-radius:10px; overflow:hidden; display:flex; gap:0;">

        {{-- 대표 이미지 (있을 때만 표시) --}}
        @if($post->cover_image)
        <a href="{{ route('posts.show', $post) }}" style="flex-shrink:0;">
            <img src="{{ Storage::url($post->cover_image) }}"
                 style="width:180px; height:140px; object-fit:cover; display:block;"
                 alt="{{ $post->title }}">
        </a>
        @endif

        <div style="padding: 18px 20px; flex:1; min-width:0;">
            {{-- 카테고리 뱃지 --}}
            @if($post->category)
            <a href="{{ route('categories.show', $post->category->slug) }}"
               style="font-size:0.75rem; color:#f4a7b9; font-weight:700; text-decoration:none; letter-spacing:0.3px;">
                {{ $post->category->name }}
            </a>
            @endif

            {{-- 제목 --}}
            <h3 style="margin: 5px 0 8px;">
                <a href="{{ route('posts.show', $post) }}"
                   style="text-decoration:none; color:#222; font-size:1rem; font-weight:700; line-height:1.4;">
                    {{ $post->title }}
                </a>
            </h3>

            {{-- 본문 미리보기 (HTML 태그 제거 후 120자) --}}
            <p style="color:#777; font-size:0.875rem; line-height:1.65; margin-bottom:12px;
                      overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                {{ Str::limit(strip_tags($post->content), 120) }}
            </p>

            {{-- 태그 --}}
            @if($post->tags->count())
            <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:12px;">
                @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag->slug) }}"
                   style="font-size:0.75rem; background:#fdeef2; color:#de8a98;
                          padding:3px 10px; border-radius:100px; text-decoration:none; font-weight:500;">
                    #{{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- 작성자 + 날짜 + 조회수 --}}
            <div style="font-size:0.78rem; color:#aaa; display:flex; gap:10px; align-items:center;">
                <span>{{ $post->user->name }}</span>
                <span>·</span>
                <span>{{ $post->created_at->format('Y.m.d') }}</span>
                <span>·</span>
                <span>조회 {{ number_format($post->views) }}</span>
            </div>
        </div>
    </article>
    @empty
    <div style="text-align:center; padding:60px 0; color:#bbb;">
        <p style="font-size:1rem;">아직 작성된 글이 없습니다.</p>
        @auth
        <a href="{{ route('posts.create') }}"
           style="display:inline-block; margin-top:16px; background:#f4a7b9; color:#fff;
                  padding:10px 24px; border-radius:20px; text-decoration:none; font-weight:600; font-size:0.9rem;">
            첫 글 작성하기
        </a>
        @endauth
    </div>
    @endforelse
</div>

{{-- 페이지네이션 --}}
@if($posts->hasPages())
<div style="margin-top: 32px; display:flex; justify-content:center;">
    {{ $posts->links() }}
</div>
@endif
@endsection

@section('sidebar')
<div class="sidebar-box">
    <h3>안내</h3>
    <p style="font-size:0.85rem; color:#777; line-height:1.7;">
        누구나 글을 쓸 수 있는 공간입니다.<br>
        @guest
        로그인 후 글쓰기를 시작해보세요.
        @endguest
    </p>
</div>
@endsection