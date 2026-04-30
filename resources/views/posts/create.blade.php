@extends('layouts.app')
@section('title', isset($post) ? '글 수정' : '글쓰기')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .form-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 28px 32px;
    }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 7px;
    }
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e8e8e8;
        border-radius: 7px;
        font-size: 0.95rem;
        font-family: inherit;
        color: #333;
        background: #fafafa;
        transition: border-color 0.15s;
    }
    .form-input:focus {
        outline: none;
        border-color: #f4a7b9;
        background: #fff;
    }
    /* Quill 에디터 높이 */
    #editor { height: 380px; background: #fff; }
    .ql-toolbar { border-radius: 7px 7px 0 0 !important; border-color: #e8e8e8 !important; }
    .ql-container { border-radius: 0 0 7px 7px !important; border-color: #e8e8e8 !important; font-size: 0.95rem; }
    .btn-submit {
        background: #f4a7b9;
        color: #fff;
        border: none;
        padding: 12px 36px;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-submit:hover { background: #de8a98; }
    .error { color: #c0392b; font-size: 0.82rem; margin-top: 5px; }
    .form-hint { font-size: 0.78rem; color: #aaa; margin-top: 5px; }
</style>
@endsection

@section('content')
<h2 style="font-size:1.25rem; font-weight:700; margin-bottom:20px;">
    {{ isset($post) ? '글 수정' : '새 글 작성' }}
</h2>

<div class="form-card">
    {{-- 수정이면 PUT, 작성이면 POST --}}
    <form action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif

        {{-- 제목 --}}
        <div class="form-group">
            <label class="form-label">제목 <span style="color:#f4a7b9;">*</span></label>
            <input type="text" name="title" class="form-input"
                   value="{{ old('title', $post->title ?? '') }}"
                   placeholder="글 제목을 입력하세요">
            @error('title') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- 카테고리 선택 --}}
        <div class="form-group">
            <label class="form-label">카테고리</label>
            <select name="category_id" class="form-input">
                <option value="">카테고리 없음</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- 태그 --}}
        <div class="form-group">
            <label class="form-label">태그</label>
            <input type="text" name="tags" class="form-input"
                   value="{{ old('tags', isset($post) ? $post->tags->pluck('name')->join(', ') : '') }}"
                   placeholder="예: Laravel, PHP, 일상">
            <p class="form-hint">쉼표(,)로 구분해서 여러 태그를 입력하세요</p>
        </div>

        {{-- 공개/비공개 --}}
        <div class="form-group">
            <label class="form-label">공개 설정</label>
            <select name="visibility" class="form-input">
                <option value="public"  {{ old('visibility', $post->visibility ?? 'public')  == 'public'  ? 'selected' : '' }}>공개</option>
                <option value="private" {{ old('visibility', $post->visibility ?? '') == 'private' ? 'selected' : '' }}>비공개</option>
            </select>
        </div>

        {{-- 대표 이미지 --}}
        <div class="form-group">
            <label class="form-label">대표 이미지</label>
            @if(isset($post) && $post->cover_image)
            <img src="{{ Storage::url($post->cover_image) }}"
                 style="height:80px; border-radius:6px; display:block; margin-bottom:8px; object-fit:cover;">
            @endif
            <input type="file" name="cover_image" accept="image/*" class="form-input"
                   style="padding: 7px 14px;">
            <p class="form-hint">최대 5MB, jpg/png/gif/webp 형식</p>
        </div>

        {{-- Quill 에디터 본문 --}}
        <div class="form-group">
            <label class="form-label">본문 <span style="color:#f4a7b9;">*</span></label>
            {{-- hidden input: Quill 내용을 여기에 담아서 서버로 전송 --}}
            <input type="hidden" name="content" id="content-input">
            {{-- Quill이 여기에 에디터를 렌더링함 --}}
            <div id="editor">{!! old('content', $post->content ?? '') !!}</div>
            @error('content') <p class="error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-submit">
            {{ isset($post) ? '수정 완료' : '등록하기' }}
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // Quill 에디터 초기화 (snow = 기본 흰색 툴바 스타일)
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: '내용을 입력하세요...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // 폼 제출 직전에 Quill의 HTML 내용을 hidden input에 담음
    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('content-input').value = quill.root.innerHTML;
    });
</script>
@endsection
