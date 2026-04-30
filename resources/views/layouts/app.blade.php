<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cat-Log')</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── 색상 변수 정의 ── */
        :root {
            --accent:    #f4a7b9;   /* 포인트 파스텔 핑크 */
            --accent-dk: #de8a98;   /* 포인트 어두운 버전 (호버) */
            --accent-lt: #fdeef2;   /* 포인트 연한 버전 (배경용) */
            --bg:        #f7f7f7;   /* 전체 배경 */
            --surface:   #ffffff;   /* 카드/패널 배경 */
            --border:    #e8e8e8;   /* 테두리 */
            --text:      #333333;   /* 본문 텍스트 */
            --muted:     #999999;   /* 보조 텍스트 */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Noto Sans KR', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
        }

        /* ── 상단 네비게이션 바 ── */
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-logo {
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--accent);
            letter-spacing: -0.5px;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-menu a {
            text-decoration: none;
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 500;
            transition: color 0.15s;
        }

        .navbar-menu a:hover { color: var(--text); }

        /* 글쓰기 버튼 */
        .btn-write {
            background: var(--accent) !important;
            color: var(--surface) !important;
            padding: 7px 18px;
            border-radius: 20px;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
            transition: background 0.15s !important;
        }
        .btn-write:hover { background: var(--accent-dk) !important; }

        /* 로그아웃 버튼 */
        .btn-logout {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            transition: color 0.15s;
        }
        .btn-logout:hover { color: var(--text); }

        /* ── 페이지 본문 레이아웃 ── */
        .container {
            max-width: 1080px;
            margin: 0 auto;
            padding: 36px 24px;
            display: flex;
            gap: 28px;
        }

        /* 메인 콘텐츠 영역 */
        .main-content { flex: 1; min-width: 0; }

        /* 오른쪽 사이드바 */
        .sidebar { width: 250px; flex-shrink: 0; }

        .sidebar-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 18px;
        }

        .sidebar-box h3 {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }

        /* ── 알림 메시지 (성공/에러) ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }
        .alert-success {
            background: var(--accent-lt);
            color: #b05070;
            border: 1px solid var(--accent);
        }
        .alert-error {
            background: #fdf0f0;
            color: #c0392b;
            border: 1px solid #f5c6c6;
        }

        /* ── 반응형: 모바일 ── */
        @media (max-width: 768px) {
            .container { flex-direction: column; padding: 20px 16px; }
            .sidebar { width: 100%; }
            .navbar { padding: 0 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- 상단 네비게이션 --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-logo">Cat-Log</a>
    <div class="navbar-menu">
        @auth
            <a href="{{ route('posts.my') }}">내 블로그</a>
            <a href="{{ route('posts.create') }}" class="btn-write">글쓰기</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">로그아웃</button>
            </form>
        @else
            <a href="{{ route('login') }}">로그인</a>
            <a href="{{ route('register') }}" class="btn-write">회원가입</a>
        @endauth
    </div>
</nav>

{{-- 페이지 본문 --}}
<div class="container">
    <main class="main-content">
        {{-- 성공/에러 메시지 자동 표시 --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <aside class="sidebar">
        @yield('sidebar')
    </aside>
</div>

@yield('scripts')
</body>
</html>
