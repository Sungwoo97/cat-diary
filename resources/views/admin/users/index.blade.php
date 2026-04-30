@extends('admin.layout')
@section('title', '회원 관리')

@section('content')
<div class="card">
    <div class="card-title">전체 회원 ({{ $users->total() }}명)</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>이름</th><th>이메일</th><th>게시글</th><th>가입일</th><th>권한</th><th>삭제</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--muted);">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td style="color:var(--muted);">{{ $user->email }}</td>
                    <td>{{ $user->posts_count }}</td>
                    <td style="color:var(--muted);">{{ $user->created_at->format('Y.m.d') }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge badge-admin">관리자</span>
                        @else
                            <span class="badge badge-gray">일반</span>
                        @endif
                    </td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('{{ $user->name }} 회원을 삭제하시겠습니까? 해당 회원의 모든 글도 삭제됩니다.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">삭제</button>
                        </form>
                        @else
                            <span style="font-size:0.78rem; color:var(--muted);">본인</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:24px;">회원이 없습니다.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div style="margin-top:16px;">{{ $users->links() }}</div>
    @endif
</div>
@endsection
