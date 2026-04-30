@extends('admin.layout')
@section('title', '카테고리 관리')

@section('content')

<div class="card">
    <div class="card-title">카테고리 추가</div>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>소유 회원 ID</label>
                <input type="number" name="user_id" class="form-input" placeholder="user_id" style="width:120px;" required>
            </div>
            <div class="form-group">
                <label>카테고리 이름</label>
                <input type="text" name="name" class="form-input" placeholder="예: 일상" style="width:200px;" required>
            </div>
            <button type="submit" class="btn btn-pink">추가</button>
        </div>
        @error('name')    <p style="color:#c0392b; font-size:0.82rem;">{{ $message }}</p> @enderror
        @error('user_id') <p style="color:#c0392b; font-size:0.82rem;">{{ $message }}</p> @enderror
    </form>
</div>

<div class="card">
    <div class="card-title">전체 카테고리 ({{ $categories->total() }}개)</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>이름</th><th>슬러그</th><th>소유자</th><th>글 수</th><th>수정</th><th>삭제</th></tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="color:var(--muted);">{{ $category->id }}</td>
                    <td>
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                              style="display:flex; gap:6px; align-items:center;">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}"
                                   class="form-input" style="width:140px;">
                            <button class="btn btn-ghost btn-sm">저장</button>
                        </form>
                    </td>
                    <td style="color:var(--muted);">{{ $category->slug }}</td>
                    <td style="color:var(--muted);">{{ $category->user->name ?? '-' }}</td>
                    <td>{{ $category->posts_count }}</td>
                    <td></td>
                    <td>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('삭제하면 해당 카테고리의 글은 카테고리 없음으로 변경됩니다. 계속할까요?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">삭제</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:24px;">카테고리가 없습니다.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div style="margin-top:16px;">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
