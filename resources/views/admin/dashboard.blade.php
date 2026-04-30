@extends('admin.layout')
@section('title', '대시보드')

@section('content')

<div class="stat-grid">
    <div class="stat-box accent">
        <div class="stat-label">전체 조회수</div>
        <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">전체 회원</div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">전체 게시글</div>
        <div class="stat-value">{{ number_format($stats['total_posts']) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">공개 게시글</div>
        <div class="stat-value">{{ number_format($stats['public_posts']) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">카테고리</div>
        <div class="stat-value">{{ number_format($stats['total_categories']) }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    <div class="card">
        <div class="card-title">최근 가입 회원</div>
        <table>
            <thead>
                <tr><th>이름</th><th>이메일</th><th>가입일</th></tr>
            </thead>
            <tbody>
                @foreach($recent_users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                        @if($user->is_admin)
                            <span class="badge badge-admin">관리자</span>
                        @endif
                    </td>
                    <td style="color: var(--muted);">{{ $user->email }}</td>
                    <td style="color: var(--muted);">{{ $user->created_at->format('m.d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-title">최근 게시글</div>
        <table>
            <thead>
                <tr><th>제목</th><th>작성자</th><th>조회</th></tr>
            </thead>
            <tbody>
                @foreach($recent_posts as $post)
                <tr>
                    <td>
                        <a href="{{ route('posts.show', $post) }}"
                           style="text-decoration: none; color: var(--text);"
                           target="_blank">
                            {{ Str::limit($post->title, 20) }}
                        </a>
                        @if($post->visibility === 'private')
                            <span class="badge badge-gray">비공개</span>
                        @endif
                    </td>
                    <td style="color: var(--muted);">{{ $post->user->name }}</td>
                    <td style="color: var(--muted);">{{ number_format($post->views) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
