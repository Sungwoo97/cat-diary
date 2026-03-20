@extends('layouts.app')
@section('title', isset($diary) ? '| 수정' : '| 새 게시물')

@section('styles')
<style>
    .create-header {
        display:flex; align-items:center; justify-content:space-between;
        padding-bottom:14px; border-bottom:1px solid #DBDBDB; margin-bottom:24px;
    }
    .create-title { font-size:1rem; font-weight:700; }
    .btn-share {
        padding:7px 16px; background:#0095F6; color:white;
        border:none; border-radius:8px; font-size:0.82rem;
        font-weight:600; cursor:pointer;
    }
    .btn-back { background:none; border:none; font-size:1.2rem; cursor:pointer; color:#262626; text-decoration:none; }

    /* 업로드 영역 */
    .upload-zone {
        width:100%; aspect-ratio:1; background:#FAFAFA;
        border:1px solid #DBDBDB; border-radius:12px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; margin-bottom:16px; overflow:hidden; position:relative;
    }
    .upload-zone:hover { background:#F2F2F2; }
    .upload-icon { font-size:3rem; margin-bottom:12px; }
    .upload-text { font-size:1.1rem; font-weight:300; color:#262626; margin-bottom:6px; }
    .upload-sub { font-size:0.82rem; color:#8E8E8E; }
    .upload-btn {
        margin-top:14px; padding:8px 20px;
        background:#0095F6; color:white;
        border:none; border-radius:8px; font-size:0.82rem;
        font-weight:600; cursor:pointer;
    }
    #upload-input { display:none; }
    #preview-img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:none; }

    /* 텍스트 영역 */
    .caption-box {
        background:white; border:1px solid #DBDBDB;
        border-radius:12px; overflow:hidden; margin-bottom:12px;
    }
    .caption-header { display:flex; align-items:center; gap:10px; padding:12px 16px 8px; }
    .cap-avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(45deg,#E1306C,#FCAF45); display:flex; align-items:center; justify-content:center; font-size:0.9rem; }
    .cap-name { font-size:0.875rem; font-weight:600; }
    .caption-input {
        width:100%; border:none; outline:none;
        padding:0 16px 14px; font-family:inherit;
        font-size:0.9rem; resize:none; min-height:120px;
        color:#262626; line-height:1.65;
    }
    .caption-input::placeholder { color:#8E8E8E; }
    .caption-footer { display:flex; justify-content:space-between; padding:8px 16px 12px; border-top:1px solid #DBDBDB; }
    .emoji-btn { background:none; border:none; font-size:1.1rem; cursor:pointer; }
    .char-count { font-size:0.75rem; color:#8E8E8E; }

    /* 옵션 행 */
    .option-row {
        display:flex; align-items:center; justify-content:space-between;
        background:white; border:1px solid #DBDBDB;
        border-radius:12px; padding:14px 16px;
        margin-bottom:8px; cursor:pointer;
    }
    .option-row:hover { background:#FAFAFA; }
    .opt-left { display:flex; align-items:center; gap:10px; font-size:0.875rem; font-weight:400; }
    .opt-right { color:#8E8E8E; font-size:0.85rem; display:flex; align-items:center; gap:6px; }

    /* 기분 패널 */
    .mood-panel {
        background:white; border:1px solid #DBDBDB;
        border-radius:12px; padding:16px; margin-bottom:8px;
        display:none;
    }
    .mood-panel.show { display:block; }
    .mood-panel-title { font-size:0.82rem; color:#8E8E8E; margin-bottom:12px; }
    .mood-grid { display:flex; flex-wrap:wrap; gap:8px; }
    .mood-chip {
        padding:7px 14px; border-radius:100px;
        border:1px solid #DBDBDB; font-size:0.82rem;
        cursor:pointer; background:white; transition:all 0.15s;
        display:flex; align-items:center; gap:5px;
    }
    .mood-chip:hover { background:#FAFAFA; border-color:#A8A8A8; }
    .mood-chip.sel { background:rgba(225,48,108,0.08); border-color:#E1306C; color:#E1306C; }

    /* 위치 패널 */
    .location-panel { background:white; border:1px solid #DBDBDB; border-radius:12px; padding:14px 16px; margin-bottom:8px; display:none; }
    .location-panel.show { display:block; }
    .loc-input { width:100%; border:none; outline:none; font-family:inherit; font-size:0.875rem; background:#EFEFEF; border-radius:8px; padding:9px 14px; }
</style>
@endsection

@section('content')

<div class="create-header">
    <a href="{{ url('/diary') }}" class="btn-back">✕</a>
    <span class="create-title">새 게시물</span>
    <button class="btn-share" onclick="document.getElementById('create-form').submit()">
        {{ isset($diary) ? '수정' : '공유하기' }}
    </button>
</div>

<form id="create-form" action="{{ isset($diary) ? url('/diary/'.$diary->id) : url('/diary') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($diary)) @method('PUT') @endif

    <!-- 이미지 업로드 -->
    <div class="upload-zone" onclick="document.getElementById('upload-input').click()">
        <img id="preview-img" src="">
        <div id="upload-placeholder">
            <div style="text-align:center;">
                <div class="upload-icon">🐱</div>
                <div class="upload-text">사진을 여기에 드래그하세요</div>
                <div class="upload-sub">JPG, PNG, GIF 파일</div>
                <button type="button" class="upload-btn" onclick="event.stopPropagation()">컴퓨터에서 선택</button>
            </div>
        </div>
        <input type="file" id="upload-input" name="photo" accept="image/*" onchange="previewPhoto(this)">
    </div>

    <!-- 제목 입력 -->
    <input type="text" name="title" class="caption-input"
        style="width:100%;background:white;border:1px solid #DBDBDB;border-radius:12px;padding:14px 16px;margin-bottom:8px;font-family:inherit;font-size:0.9rem;outline:none;"
        placeholder="제목을 입력하세요..."
        value="{{ isset($diary) ? $diary->title : old('title') }}" required>

    <!-- 글 내용 -->
    <div class="caption-box">
        <div class="caption-header">
            <div class="cap-avatar">🐾</div>
            <span class="cap-name">@auth {{ auth()->user()->name }} @else 집사님 @endauth</span>
        </div>
        <textarea name="content" id="caption-ta" class="caption-input"
            placeholder="문구를 입력하거나 설문을 추가하세요..."
            oninput="updateCount(this)" required>{{ isset($diary) ? $diary->content : old('content') }}</textarea>
        <div class="caption-footer">
            <button type="button" class="emoji-btn">😊</button>
            <span class="char-count"><span id="char-n">0</span>/2,200</span>
        </div>
    </div>

    <!-- 기분 선택 -->
    <div class="option-row" onclick="togglePanel('mood-panel')">
        <span class="opt-left">😸 기분 태그 추가</span>
        <span class="opt-right"><span id="mood-val">없음</span> ›</span>
    </div>
    <div class="mood-panel" id="mood-panel">
        <div class="mood-panel-title">오늘 냥이 기분은?</div>
        <div class="mood-grid">
            @foreach([
                ['😸','행복'],['😹','웃김'],['😿','슬픔'],
                ['😴','피곤'],['😡','화남'],['🤒','아픔'],
                ['😋','냠냠'],['😻','사랑스러움'],['🙀','깜짝'],
            ] as $m)
            <button type="button" class="mood-chip" onclick="selectMood('{{ $m[0] }} {{ $m[1] }}', this)">
                {{ $m[0] }} {{ $m[1] }}
            </button>
            @endforeach
        </div>
        <input type="hidden" name="mood" id="mood-input" value="{{ isset($diary) ? $diary->mood : '' }}">
    </div>

    <!-- 날씨 -->
    <div class="option-row" onclick="togglePanel('weather-panel')">
        <span class="opt-left">🌤️ 날씨 추가</span>
        <span class="opt-right"><span id="weather-val">없음</span> ›</span>
    </div>
    <div class="mood-panel" id="weather-panel">
        <div class="mood-panel-title">오늘 날씨는?</div>
        <div class="mood-grid">
            @foreach(['☀️ 맑음','⛅ 흐림','🌧️ 비','❄️ 눈','🌬️ 바람','🌈 무지개'] as $w)
            <button type="button" class="mood-chip" onclick="selectWeather('{{ $w }}', this)">{{ $w }}</button>
            @endforeach
        </div>
        <input type="hidden" name="weather" id="weather-input" value="{{ isset($diary) ? $diary->weather : '' }}">
    </div>

    <!-- 위치 -->
    <div class="option-row" onclick="togglePanel('location-panel')">
        <span class="opt-left">📍 위치 추가</span>
        <span class="opt-right">›</span>
    </div>
    <div class="location-panel" id="location-panel">
        <input type="text" name="location" class="loc-input" placeholder="🔍 위치 검색">
    </div>

    <!-- 날짜 -->
    <div class="option-row">
        <span class="opt-left">🗓️ 날짜</span>
        <input type="date" name="diary_date"
            style="border:none;outline:none;font-family:inherit;font-size:0.82rem;color:#262626;background:none;"
            value="{{ isset($diary) ? $diary->diary_date : now()->format('Y-m-d') }}">
    </div>

</form>

@endsection

@section('rightbar')
<div style="font-size:0.82rem;color:#8E8E8E;line-height:1.7;">
    <p style="font-weight:600;color:#262626;margin-bottom:10px;">게시물 작성 팁</p>
    <p>🐱 냥이와의 특별한 순간을 담아보세요</p>
    <p style="margin-top:8px;">📸 정사각형 비율의 사진이 가장 잘 보여요</p>
    <p style="margin-top:8px;">🏷️ 태그를 추가하면 더 많은 냥이집사들이 볼 수 있어요</p>
    <p style="margin-top:8px;">😸 기분 태그로 오늘의 냥이 상태를 표현해봐요</p>
</div>
@endsection

@section('scripts')
<script>
function updateCount(el) {
    document.getElementById('char-n').textContent = el.value.length;
}

function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('preview-img');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('upload-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePanel(id) {
    const el = document.getElementById(id);
    el.classList.toggle('show');
}

function selectMood(val, btn) {
    document.querySelectorAll('#mood-panel .mood-chip').forEach(c => c.classList.remove('sel'));
    btn.classList.add('sel');
    document.getElementById('mood-input').value = val;
    document.getElementById('mood-val').textContent = val;
}

function selectWeather(val, btn) {
    document.querySelectorAll('#weather-panel .mood-chip').forEach(c => c.classList.remove('sel'));
    btn.classList.add('sel');
    document.getElementById('weather-input').value = val;
    document.getElementById('weather-val').textContent = val;
}

document.addEventListener('DOMContentLoaded', () => {
    const ta = document.getElementById('caption-ta');
    if(ta) document.getElementById('char-n').textContent = ta.value.length;
});
</script>
@endsection
