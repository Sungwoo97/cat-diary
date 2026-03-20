<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>묘생일기 @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #FAFAFA;
            --surface:  #FFFFFF;
            --border:   #DBDBDB;
            --text:     #262626;
            --muted:    #8E8E8E;
            --accent:   #E1306C;
            --blue:     #0095F6;
            --sidebar-w: 244px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Noto Sans KR',-apple-system,sans-serif; background:var(--bg); color:var(--text); font-size:14px; }

        /* 사이드바 */
        .sidebar {
            position:fixed; left:0; top:0; bottom:0;
            width:var(--sidebar-w);
            background:var(--surface);
            border-right:1px solid var(--border);
            padding:8px 12px;
            display:flex; flex-direction:column;
            z-index:100;
        }
        .sidebar-logo {
            font-family:'Dancing Script',cursive; font-size:1.75rem; font-weight:700;
            background:linear-gradient(45deg,#E1306C,#F77737,#FCAF45);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
            padding:16px 12px 22px; text-decoration:none; display:block;
        }
        .nav-item {
            display:flex; align-items:center; gap:14px;
            padding:12px; border-radius:10px;
            text-decoration:none; color:var(--text);
            font-size:0.9375rem; font-weight:400;
            transition:background 0.15s;
            cursor:pointer; border:none; background:none;
            width:100%; text-align:left;
        }
        .nav-item:hover { background:#F2F2F2; }
        .nav-item.active { font-weight:700; }
        .nav-icon { width:26px; height:26px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:1.3rem; }
        .sidebar-bottom { margin-top:auto; }

        /* 메인 */
        .main { margin-left:var(--sidebar-w); display:flex; justify-content:center; padding:0 20px; min-height:100vh; }
        .feed-col { width:470px; padding:30px 0; }
        .right-col { width:320px; padding:30px 0 30px 46px; position:sticky; top:0; height:100vh; overflow-y:auto; }

        /* 스토리 */
        .stories-bar {
            background:var(--surface); border:1px solid var(--border);
            border-radius:12px; padding:14px 16px;
            display:flex; gap:16px; overflow-x:auto;
            scrollbar-width:none; margin-bottom:24px;
        }
        .stories-bar::-webkit-scrollbar { display:none; }
        .story-item { display:flex; flex-direction:column; align-items:center; gap:6px; cursor:pointer; flex-shrink:0; }
        .story-ring { width:56px; height:56px; border-radius:50%; padding:2px; background:linear-gradient(45deg,#E1306C,#F77737,#FCAF45); }
        .story-ring.seen { background:#C7C7C7; }
        .story-avatar { width:100%; height:100%; border-radius:50%; background:var(--bg); border:2px solid white; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
        .story-name { font-size:0.72rem; color:var(--text); text-align:center; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

        /* 포스트 카드 */
        .post-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; margin-bottom:24px; overflow:hidden; }
        .post-header { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; }
        .post-author { display:flex; align-items:center; gap:10px; text-decoration:none; color:var(--text); }
        .avatar-wrap { width:34px; height:34px; border-radius:50%; padding:2px; background:linear-gradient(45deg,#E1306C,#FCAF45); flex-shrink:0; }
        .avatar-inner { width:100%; height:100%; border-radius:50%; background:var(--bg); border:2px solid white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; }
        .author-name { font-weight:600; font-size:0.875rem; }
        .post-time { font-size:0.75rem; color:var(--muted); }
        .post-more { font-size:1.3rem; color:var(--text); background:none; border:none; cursor:pointer; padding:4px; line-height:1; }
        .post-image { width:100%; aspect-ratio:1; background:linear-gradient(135deg,#FFF0F5,#FFF8F0); display:flex; align-items:center; justify-content:center; font-size:6rem; }
        .post-image img { width:100%; height:100%; object-fit:cover; }
        .post-actions { display:flex; align-items:center; padding:12px 16px 6px; gap:16px; }
        .act-btn { background:none; border:none; cursor:pointer; padding:2px; display:flex; align-items:center; color:var(--text); transition:transform 0.1s; font-size:1.4rem; }
        .act-btn:active { transform:scale(0.85); }
        .act-btn.liked { color:#E1306C; }
        .save-btn { margin-left:auto; }
        .post-likes { padding:0 16px 5px; font-weight:600; font-size:0.875rem; }
        .post-caption { padding:0 16px 8px; line-height:1.65; font-size:0.875rem; }
        .caption-user { font-weight:600; margin-right:5px; }
        .post-tags { color:var(--blue); }
        .more-comments { padding:0 16px 8px; color:var(--muted); font-size:0.875rem; cursor:pointer; background:none; border:none; display:block; text-align:left; }
        .post-date { padding:0 16px 10px; color:var(--muted); font-size:0.72rem; letter-spacing:0.3px; }
        .mood-badge { display:inline-block; padding:3px 10px; border-radius:100px; font-size:0.72rem; background:rgba(225,48,108,0.08); color:var(--accent); margin:0 16px 10px; border:1px solid rgba(225,48,108,0.2); }
        .comment-row { display:flex; align-items:center; padding:10px 16px 14px; border-top:1px solid var(--border); gap:10px; }
        .comment-input { flex:1; border:none; outline:none; font-family:inherit; font-size:0.875rem; background:none; color:var(--text); }
        .comment-input::placeholder { color:var(--muted); }
        .comment-submit { background:none; border:none; color:var(--blue); font-weight:600; font-size:0.875rem; cursor:pointer; opacity:0.4; }
        .comment-submit.on { opacity:1; }

        /* 우측 */
        .profile-mini { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
        .profile-mini-left { display:flex; align-items:center; gap:14px; }
        .avatar-lg { width:56px; height:56px; border-radius:50%; background:linear-gradient(45deg,#E1306C,#FCAF45); padding:2px; }
        .avatar-lg-inner { width:100%; height:100%; border-radius:50%; background:var(--bg); border:2px solid white; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .pm-name { font-weight:600; font-size:0.875rem; }
        .pm-sub  { font-size:0.8rem; color:var(--muted); }
        .btn-link { background:none; border:none; color:var(--blue); font-size:0.8rem; font-weight:600; cursor:pointer; }
        .section-label { color:var(--muted); font-weight:600; font-size:0.8rem; margin-bottom:14px; display:flex; justify-content:space-between; }
        .suggest-item { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
        .suggest-left { display:flex; align-items:center; gap:10px; }
        .sug-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#E1306C,#FCAF45); display:flex; align-items:center; justify-content:center; font-size:1rem; }
        .sug-name { font-size:0.8rem; font-weight:600; }
        .sug-sub  { font-size:0.72rem; color:var(--muted); }
        .btn-follow { background:none; border:none; color:var(--blue); font-size:0.78rem; font-weight:600; cursor:pointer; }
        .rf { margin-top:24px; font-size:0.7rem; color:var(--muted); line-height:2; }

        /* 반응형 */
        @media(max-width:1100px) { .right-col { display:none; } }
        @media(max-width:768px) {
            .sidebar { width:72px; }
            .sidebar-logo, .nav-item span:not(.nav-icon), .nav-label { display:none; }
            .nav-item { justify-content:center; }
            .main { margin-left:72px; }
            .feed-col { width:100%; max-width:470px; }
        }
    </style>
    @yield('styles')
</head>
<body>

<aside class="sidebar">
    <a href="{{ url('/') }}" class="sidebar-logo">묘생일기</a>
    <nav>
        <a href="{{ url('/') }}"           class="nav-item {{ request()->is('/') ? 'active' : '' }}"><span class="nav-icon">🏠</span><span class="nav-label">홈</span></a>
        <a href="#"                        class="nav-item"><span class="nav-icon">🔍</span><span class="nav-label">검색</span></a>
        <a href="{{ url('/diary') }}"      class="nav-item {{ request()->is('diary') ? 'active' : '' }}"><span class="nav-icon">🧭</span><span class="nav-label">탐색</span></a>
        <a href="#"                        class="nav-item"><span class="nav-icon">🎞️</span><span class="nav-label">릴스</span></a>
        <a href="#"                        class="nav-item"><span class="nav-icon">💌</span><span class="nav-label">메시지</span></a>
        <a href="#"                        class="nav-item"><span class="nav-icon">🔔</span><span class="nav-label">알림</span></a>
        <a href="{{ url('/diary/create') }}" class="nav-item {{ request()->is('diary/create') ? 'active' : '' }}"><span class="nav-icon">➕</span><span class="nav-label">만들기</span></a>
        <a href="#"                        class="nav-item"><span class="nav-icon">😸</span><span class="nav-label">프로필</span></a>
    </nav>
    <div class="sidebar-bottom">
        @auth
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item"><span class="nav-icon">🚪</span><span class="nav-label">로그아웃</span></button>
        </form>
        @else
        <a href="{{ url('/login') }}" class="nav-item"><span class="nav-icon">🔑</span><span class="nav-label">로그인</span></a>
        @endauth
    </div>
</aside>

<div class="main">
    <div class="feed-col">@yield('content')</div>
    <aside class="right-col">@yield('rightbar')</aside>
</div>

@yield('scripts')
</body>
</html>
