<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '관리자') · Cat-Log</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent:    #f4a7b9;
            --accent-dk: #de8a98;
            --bg:        #f7f7f7;
            --surface:   #ffffff;
            --border:    #e8e8e8;
            --text:      #333333;
            --muted:     #999999;
            --sidebar-w: 220px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans KR', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; display: flex; min-height: 100vh; }

        /* 사이드바 */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--surface); border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 22px 20px 18px;
            font-size: 1.1rem; font-weight: 700; color: var(--accent);
            text-decoration: none; border-bottom: 1px solid var(--border);
            display: block;
        }
        .sidebar-logo span { font-size: 0.75rem; color: var(--muted); font-weight: 400; display: block; margin-top: 2px; }
        .sidebar-nav { padding: 12px 0; flex: 1; }
        .nav-section { padding: 12px 20px 6px; font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; }
        .nav-item {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 20px; text-decoration: none;
            color: var(--text); font-size: 0.875rem; font-weight: 500;
            transition: background 0.1s, color 0.1s;
        }
        .nav-item:hover { background: var(--bg); }
        .nav-item.active { background: #fdeef2; color: var(--accent-dk); font-weight: 600; }
        .nav-item .icon { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-footer { padding: 16px 20px; border-top: 1px solid var(--border); }
        .sidebar-footer a { font-size: 0.82rem; color: var(--muted); text-decoration: none; }
        .sidebar-footer a:hover { color: var(--text); }

        /* 메인 영역 */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 54px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 1rem; font-weight: 700; }
        .topbar-user { font-size: 0.82rem; color: var(--muted); }
        .content { padding: 28px; flex: 1; }

        /* 알림 */
        .alert { padding: 11px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; }
        .alert-success { background: #fdeef2; color: #b05070; border: 1px solid var(--accent); }
        .alert-error   { background: #fdf0f0; color: #c0392b; border: 1px solid #f5c6c6; }

        /* 카드 */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 22px 24px; margin-bottom: 20px; }
        .card-title { font-size: 0.85rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }

        /* 통계 그리드 */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-box { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
        .stat-label { font-size: 0.78rem; color: var(--muted); margin-bottom: 8px; }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: var(--text); }
        .stat-box.accent { border-color: var(--accent); background: #fdeef2; }
        .stat-box.accent .stat-value { color: var(--accent-dk); }

        /* 테이블 */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        th { text-align: left; padding: 10px 14px; font-size: 0.78rem; font-weight: 700; color: var(--muted); border-bottom: 2px solid var(--border); white-space: nowrap; }
        td { padding: 11px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg); }

        /* 버튼 */
        .btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 16px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; text-decoration: none; transition: opacity 0.15s; }
        .btn:hover { opacity: 0.85; }
        .btn-pink   { background: var(--accent); color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-ghost  { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-sm { padding: 5px 12px; font-size: 0.78rem; }

        /* 폼 인풋 */
        .form-row { display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .form-input {
            padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px;
            font-family: inherit; font-size: 0.875rem; background: var(--bg);
            outline: none; transition: border-color 0.15s;
        }
        .form-input:focus { border-color: var(--accent); background: var(--surface); }

        /* 뱃지 */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 0.72rem; font-weight: 600; }
        .badge-pink    { background: #fdeef2; color: var(--accent-dk); }
        .badge-gray    { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
        .badge-admin   { background: #fff3cd; color: #856404; }
    </style>
    @yield('styles')
</head>
<body>

<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        Cat-Log Admin
        <span>관리자 페이지</span>
    </a>
    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> 대시보드
        </a>

        <div class="nav-section">관리</div>
        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="icon">👤</span> 회원 관리
        </a>
        <a href="{{ route('admin.posts.index') }}"
           class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <span class="icon">📝</span> 게시글 관리
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="icon">🗂</span> 카테고리 관리
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('home') }}">← 블로그로 돌아가기</a>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('title', '대시보드')</span>
        <span class="topbar-user">{{ auth()->user()->name }}</span>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>
