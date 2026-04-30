@extends('admin.layout')
@section('title', '게시글 관리')

@section('content')
<div class="card">
    <div class="card-title">전체 게시글 ({{ $posts->total() }}개)</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>제목</th><th>작성자</th><th>카테고리</th><th>공개</th><th>조회</th><th>작성일</th><th>삭제</th></tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td style="color:var(--muted);">{{ $post->id }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" target="_blank"
                           style="text-decoration:none; color:var(--text); font-weight:500;">
                            {{ Str::limit($post->title, 30) }}
                        </a>
                    </td>
                    <td style="color:var(--muted);">{{ $post->user->name }}</td>
                    <td style="color:var(--muted);">{{ $post->category->name ?? '-' }}</td>
                    <td>
                        @if($post->visibility === 'public')
                            <span class="badge badge-pink">공개</span>
                        @else
                            <span class="badge badge-gray">비공개</span>
                        @endif
                    </td>
                    <td>{{ number_format($post->views) }}</td>
                    <td style="color:var(--muted);">{{ $post->created_at->format('Y.m.d') }}</td>
                    <td>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                              onsubmit="return confirm('게시글을 삭제하시겠습니까?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">삭제</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:var(--muted); padding:24px;">게시글이 없습니다.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
        <div style="margin-top:16px;">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
