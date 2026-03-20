@extends('layouts.app')
@section('title', '| 홈')

@section('content')

{{-- 스토리 --}}
<div class="stories-bar">
    <div class="story-item">
        <div class="story-ring">
            <div class="story-avatar">➕</div>
        </div>
        <span class="story-name">내 스토리</span>
    </div>
    @foreach([
        ['🐱','나비맘',''],
        ['🐈','루시집사','seen'],
        ['😺','모찌냥',''],
        ['🐾','코코네','seen'],
        ['🙀','두부랑',''],
        ['😸','하루냥',''],
        ['🐈‍⬛','밤순이네','seen'],
    ] as $s)
    <div class="story-item">
        <div class="story-ring {{ $s[2] }}">
            <div class="story-avatar">{{ $s[0] }}</div>
        </div>
        <span class="story-name">{{ $s[1] }}</span>
    </div>
    @endforeach
</div>

{{-- 포스트 피드 --}}
@php
$posts = [
    [
        'emoji'   => '🐱',
        'user'    => '나비맘',
        'time'    => '3시간 전',
        'cat'     => '🐱',
        'likes'   => '1,284',
        'mood'    => '😸 행복',
        'caption' => '오늘 나비가 창가에서 햇살을 받으며 낮잠을 자고 있었어요. 세상 평화로운 표정에 저도 힐링됐습니다 🌞',
        'tags'    => '#냥스타그램 #고양이 #묘생일기 #나비',
        'comments'=> 47,
    ],
    [
        'emoji'   => '😹',
        'user'    => '루시집사',
        'time'    => '5시간 전',
        'cat'     => '🐈',
        'likes'   => '892',
        'mood'    => '😹 웃김',
        'caption' => '루시가 오늘 또 박스에 들어가서 안 나오네요... 택배가 도착할 때마다 이러는데 어쩜 이렇게 귀여울까요 📦',
        'tags'    => '#루시 #박스고양이 #냥이일상 #묘생일기',
        'comments'=> 23,
    ],
    [
        'emoji'   => '😺',
        'user'    => '모찌냥이',
        'time'    => '어제',
        'cat'     => '😺',
        'likes'   => '2,104',
        'mood'    => '😋 간식타임',
        'caption' => '모찌의 최애 간식 타임🍗 언제 봐도 먹는 모습이 제일 귀여워요. 오늘도 한 봉지를 순삭했습니다 ㅋㅋ',
        'tags'    => '#모찌 #간식냥 #고양이먹방 #묘생일기',
        'comments'=> 61,
    ],
];
@endphp

@foreach($posts as $post)
<div class="post-card">
    <div class="post-header">
        <a href="#" class="post-author">
            <div class="avatar-wrap">
                <div class="avatar-inner">{{ $post['emoji'] }}</div>
            </div>
            <div>
                <div class="author-name">{{ $post['user'] }}</div>
                <div class="post-time">{{ $post['time'] }}</div>
            </div>
        </a>
        <button class="post-more">···</button>
    </div>

    <div class="post-image">{{ $post['cat'] }}</div>

    <div class="post-actions">
        <button class="act-btn" onclick="toggleLike(this)">🤍</button>
        <button class="act-btn">💬</button>
        <button class="act-btn">📤</button>
        <button class="act-btn save-btn">🔖</button>
    </div>

    <div class="post-likes">좋아요 {{ $post['likes'] }}개</div>
    <span class="mood-badge">{{ $post['mood'] }}</span>

    <div class="post-caption">
        <span class="caption-user">{{ $post['user'] }}</span>
        {{ $post['caption'] }}
        <span class="post-tags"> {{ $post['tags'] }}</span>
    </div>

    <button class="more-comments">댓글 {{ $post['comments'] }}개 모두 보기</button>
    <div class="post-date">{{ $post['time'] }}</div>

    <div class="comment-row">
        <span style="font-size:1.2rem">😊</span>
        <input class="comment-input" type="text" placeholder="댓글 달기..."
            oninput="this.nextElementSibling.className='comment-submit '+(this.value?'on':'')">
        <button class="comment-submit">게시</button>
    </div>
</div>
@endforeach

@endsection

{{-- 우측 사이드바 --}}
@section('rightbar')

<div class="profile-mini">
    <div class="profile-mini-left">
        <div class="avatar-lg">
            <div class="avatar-lg-inner">🐾</div>
        </div>
        <div>
            <div class="pm-name">집사님</div>
            <div class="pm-sub">냥이집사</div>
        </div>
    </div>
    <button class="btn-link">전환</button>
</div>

<div class="section-label">
    <span>팔로우 추천</span>
    <button class="btn-link">모두 보기</button>
</div>

@foreach([
    ['🐱','나비맘','나비 집사 🐱'],
    ['🐈','루시집사','루시와 함께'],
    ['😸','모찌냥','모찌 스타그램'],
    ['🐾','두부사랑','두부냥 일상'],
    ['🙀','하루하루','하루냥 일기'],
] as $s)
<div class="suggest-item">
    <div class="suggest-left">
        <div class="sug-avatar">{{ $s[0] }}</div>
        <div>
            <div class="sug-name">{{ $s[1] }}</div>
            <div class="sug-sub">{{ $s[2] }}</div>
        </div>
    </div>
    <button class="btn-follow">팔로우</button>
</div>
@endforeach

<div class="rf">
    소개 · 도움말 · 홍보 · API · 개인정보처리방침<br>
    약관 · 위치 · 언어<br><br>
    © 2026 묘생일기
</div>
@endsection

@section('scripts')
<script>
function toggleLike(btn) {
    if (btn.textContent === '🤍') {
        btn.textContent = '❤️';
        btn.classList.add('liked');
        const likes = btn.closest('.post-card').querySelector('.post-likes');
        const n = parseInt(likes.textContent.replace(/[^0-9]/g,'').replace(',',''));
        likes.textContent = '좋아요 ' + (n+1).toLocaleString() + '개';
    } else {
        btn.textContent = '🤍';
        btn.classList.remove('liked');
        const likes = btn.closest('.post-card').querySelector('.post-likes');
        const n = parseInt(likes.textContent.replace(/[^0-9]/g,'').replace(',',''));
        likes.textContent = '좋아요 ' + (n-1).toLocaleString() + '개';
    }
}
</script>
@endsection
