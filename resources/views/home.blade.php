@extends('layouts.app')
@section('title', '| 홈')

@section('content')

{{-- 스토리 --}}
<div class="stories-bar">
    <div class="story-item">
        <div class="story-ring">
            <div class="story-avatar">+</div>
        </div>
        <span class="story-name">내 스토리</span>
    </div>
    @foreach([['user1','seen'],['user2',''],['user3','seen'],['user4',''],['user5','']] as $s)
    <div class="story-item">
        <div class="story-ring {{ $s[1] }}">
            <div class="story-avatar" style="font-size:0.75rem;font-weight:600;">{{ strtoupper(substr($s[0],0,2)) }}</div>
        </div>
        <span class="story-name">{{ $s[0] }}</span>
    </div>
    @endforeach
</div>

{{-- 피드 --}}
@forelse($diaries as $diary)
<div class="post-card">
    <div class="post-header">
        <a href="#" class="post-author">
            <div class="avatar-wrap">
                <div class="avatar-inner" style="font-size:0.75rem;font-weight:600;">
                    {{ strtoupper(substr($diary->user->name ?? 'U', 0, 2)) }}
                </div>
            </div>
            <div>
                <div class="author-name">{{ $diary->user->name ?? '알수없음' }}</div>
                <div class="post-time">{{ $diary->created_at->diffForHumans() }}</div>
            </div>
        </a>
        <button class="post-more">...</button>
    </div>

    {{-- 사진 --}}
    <div class="post-image">
        @if($diary->photo)
            <img src="{{ asset('storage/' . $diary->photo) }}" alt="{{ $diary->title }}">
        @else
            <div style="font-size:5rem;opacity:0.3;">
                {{ substr($diary->title, 0, 1) }}
            </div>
        @endif
    </div>

    <div class="post-actions">
        <button class="act-btn" data-id="{{ $diary->id }}" onclick="toggleLike(this)">
            <span class="heart-icon">♡</span>
        </button>
        <button class="act-btn">✉</button>
        <button class="act-btn">↗</button>
        <button class="act-btn save-btn">⊡</button>
    </div>

    <div class="post-likes" id="likes-{{ $diary->id }}">
        좋아요 {{ number_format($diary->likes) }}개
    </div>

    @if($diary->mood)
        <span class="mood-badge">{{ $diary->mood }}</span>
    @endif

    <div class="post-caption">
        <span class="caption-user">{{ $diary->user->name ?? '알수없음' }}</span>
        {{ $diary->content }}
    </div>

    <div class="post-date">
        {{ $diary->diary_date->format('Y년 m월 d일') }}
    </div>

    <div class="comment-row">
        <span style="font-size:1.2rem">:)</span>
        <input class="comment-input" type="text" placeholder="댓글 달기..."
            oninput="this.nextElementSibling.className='comment-submit '+(this.value?'on':'')">
        <button class="comment-submit">게시</button>
    </div>
</div>
@empty
<div style="text-align:center;padding:60px 20px;background:white;border:1px solid #DBDBDB;border-radius:12px;">
    <p style="font-size:1rem;font-weight:600;margin-bottom:8px;">아직 게시물이 없습니다</p>
    <p style="color:#8E8E8E;font-size:0.875rem;margin-bottom:20px;">첫 번째 일기를 작성해보세요.</p>
    <a href="{{ route('diary.create') }}"
       style="background:#0095F6;color:white;padding:8px 20px;border-radius:8px;text-decoration:none;font-size:0.875rem;font-weight:600;">
        일기 작성하기
    </a>
</div>
@endforelse

{{-- 페이지네이션 --}}
@if($diaries->hasPages())
<div style="margin-top:20px;">
    {{ $diaries->links() }}
</div>
@endif

@endsection

@section('rightbar')
@auth
<div class="profile-mini">
    <div class="profile-mini-left">
        <div class="avatar-lg">
            <div class="avatar-lg-inner" style="font-size:1rem;font-weight:600;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
        <div>
            <div class="pm-name">{{ auth()->user()->name }}</div>
            <div class="pm-sub">{{ auth()->user()->email }}</div>
        </div>
    </div>
</div>
@endauth

<div class="section-label">
    <span>최근 작성자</span>
</div>

@foreach($recentUsers as $user)
<div class="suggest-item">
    <div class="suggest-left">
        <div class="sug-avatar" style="font-size:0.8rem;font-weight:600;">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
            <div class="sug-name">{{ $user->name }}</div>
            <div class="sug-sub">일기 {{ $user->diaries_count }}개</div>
        </div>
    </div>
</div>
@endforeach

<div class="rf">
    소개 · 도움말 · 개인정보처리방침<br>
    © 2026 묘생일기
</div>
@endsection

@section('scripts')
<script>
function toggleLike(btn) {
    const id = btn.dataset.id;
    fetch(`/diary/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const heart = btn.querySelector('.heart-icon');
        heart.textContent = heart.textContent === '♡' ? '♥' : '♡';
        document.getElementById(`likes-${id}`).textContent = `좋아요 ${data.likes.toLocaleString()}개`;
    });
}
</script>
@endsection