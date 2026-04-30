@extends('layouts.app')
@section('title', $post->title)

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .post-wrap {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 32px 36px;
    }
    .post-category {
        font-size: 0.78rem;
        color: #f4a7b9;
        font-weight: 700;
        text-decoration: none;
        letter-spacing: 0.3px;
        display: block;
        margin-bottom: 10px;
    }
    .post-title {
        font-size: 1.7rem;
        font-weight: 700;
        line-height: 1.4;
        color: #222;
        margin-bottom: 14px;
    }
    .post-meta {
        font-size: 0.82rem;
        color: #aaa;
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .post-cover {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 28px;
    }
    /* Quill 본문 출력 (툴바 없이 본문만) */
    .post-body { line-height: 1.85; font-size: 0.97rem; color: #333; }
    .post-body .ql-editor { padding: 0; }
    .post-body .ql-container.ql-snow { border: none; }
    .post-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }
    .tag-link {
        font-size: 0.8rem;
        background: #fdeef2;
        color: #de8a98;
        padding: 5px 13px;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 500;
    }
    .post-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }
    .btn-edit {
        padding: 8px 22px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        background: #f5f5f5;
        color: #555;
        border: 1px solid #e0e0e0;
    }
    .btn-delete {
        padding: 8px 22px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: none;
        color: #c0392b;
        border: 1px solid #f5c6c6;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<article class="post-wrap">
    {{-- 카테고리 --}}
    @if($post->category)
    <a href="{{ route('categories.show', $post->category->slug) }}" class="post-category">
        {{ $post->category->name }}
    </a>
    @endif

    {{-- 제목 --}}
    <h1 class="post-title">{{ $post->title }}</h1>

    {{-- 메타 정보 --}}
    <div class="post-meta">
        <span>{{ $post->user->name }}</span>
        <span>·</span>
        <span>{{ $post->created_at->format('Y년 m월 d일') }}</span>
        <span>·</span>
        <span id="view-count">조회 {{ number_format($post->views) }}</span>
        @if($post->visibility === 'private')
        <span style="color:#de8a98; margin-left:4px;">🔒 비공개</span>
        @endif
    </div>

    {{-- 대표 이미지 --}}
    @if($post->cover_image)
    <img src="{{ Storage::url($post->cover_image) }}" class="post-cover" alt="{{ $post->title }}">
    @endif

    {{-- 본문 (Quill이 생성한 HTML을 그대로 출력) --}}
    <div class="post-body ql-editor">
        {!! $post->content !!}
    </div>

    {{-- 태그 --}}
    @if($post->tags->count())
    <div class="post-tags">
        @foreach($post->tags as $tag)
        <a href="{{ route('tags.show', $tag->slug) }}" class="tag-link">#{{ $tag->name }}</a>
        @endforeach
    </div>
    @endif

    {{-- 수정/삭제 버튼 (본인 글만 표시) --}}
    @auth
    @if(Auth::id() === $post->user_id)
    <div class="post-actions">
        <a href="{{ route('posts.edit', $post) }}" class="btn-edit">수정</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST"
              onsubmit="return confirm('정말 삭제하시겠습니까?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete">삭제</button>
        </form>
    </div>
    @endif
    @endauth
</article>
@endsection

@section('scripts')
<script>
    // 페이지 진입 시 조회수를 AJAX로 증가시킴 (새로고침해도 별도 카운트 안 됨)
    fetch('{{ route('posts.views', $post) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('view-count').textContent = '조회 ' + data.views.toLocaleString();
    });
</script>
@endsection
