@extends('layouts.app')
@section('title', '| 탐색')

@section('styles')
<style>
    .explore-header {
        display:flex; justify-content:space-between; align-items:center;
        margin-bottom:20px;
    }
    .explore-title { font-size:1rem; font-weight:700; }
    .btn-write {
        display:flex; align-items:center; gap:6px;
        padding:7px 16px; background:#0095F6; color:white;
        border:none; border-radius:8px; font-size:0.82rem;
        font-weight:600; cursor:pointer; text-decoration:none;
    }

    /* 검색바 */
    .search-bar {
        display:flex; align-items:center; gap:8px;
        background:#EFEFEF; border-radius:10px;
        padding:8px 14px; margin-bottom:20px;
    }
    .search-icon { color:#8E8E8E; font-size:0.9rem; }
    .search-input { border:none; background:none; outline:none; font-family:inherit; font-size:0.875rem; width:100%; }

    /* 필터 탭 */
    .filter-tabs { display:flex; gap:0; margin-bottom:20px; border-bottom:1px solid #DBDBDB; }
    .filter-tab {
        padding:10px 20px; font-size:0.875rem; font-weight:400;
        cursor:pointer; border:none; background:none;
        color:#8E8E8E; border-bottom:2px solid transparent; margin-bottom:-1px;
    }
    .filter-tab.active { color:#262626; font-weight:600; border-bottom:2px solid #262626; }

    /* 그리드 */
    .grid-3 {
        display:grid; grid-template-columns:repeat(3,1fr);
        gap:3px; margin-bottom:24px;
    }
    .grid-item {
        aspect-ratio:1; background:linear-gradient(135deg,#FFF0F5,#FFF8F0);
        position:relative; overflow:hidden; cursor:pointer;
    }
    .grid-item.wide { grid-column:span 2; }
    .grid-item.tall { grid-row:span 2; }
    .grid-emoji { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:3.5rem; transition:transform 0.2s; }
    .grid-item:hover .grid-emoji { transform:scale(1.08); }
    .grid-item:hover .grid-overlay { opacity:1; }
    .grid-overlay {
        position:absolute; inset:0;
        background:rgba(0,0,0,0.3);
        display:flex; align-items:center; justify-content:center;
        gap:20px; opacity:0; transition:opacity 0.2s;
        color:white; font-size:0.875rem; font-weight:600;
    }
    .ov-stat { display:flex; align-items:center; gap:5px; }

    /* 일기 리스트 뷰 */
    .list-view { display:none; flex-direction:column; gap:16px; }
    .diary-card {
        background:white; border:1px solid #DBDBDB;
        border-radius:12px; overflow:hidden;
    }
    .dc-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; }
    .dc-author { display:flex; align-items:center; gap:10px; text-decoration:none; color:#262626; }
    .dc-avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(45deg,#E1306C,#FCAF45); display:flex; align-items:center; justify-content:center; font-size:0.875rem; }
    .dc-name { font-weight:600; font-size:0.82rem; }
    .dc-time { font-size:0.72rem; color:#8E8E8E; }
    .dc-img { width:100%; height:200px; background:linear-gradient(135deg,#FFF0F5,#FFE4E8); display:flex; align-items:center; justify-content:center; font-size:5rem; }
    .dc-actions { display:flex; gap:14px; padding:10px 16px 6px; }
    .dc-act { background:none; border:none; font-size:1.3rem; cursor:pointer; }
    .dc-save { margin-left:auto; }
    .dc-likes { padding:0 16px 5px; font-size:0.82rem; font-weight:600; }
    .dc-caption { padding:0 16px 10px; font-size:0.82rem; line-height:1.5; }
    .dc-cap-user { font-weight:600; margin-right:5px; }
    .dc-tags { color:#0095F6; }
    .dc-date { padding:0 16px 12px; font-size:0.72rem; color:#8E8E8E; }
</style>
@endsection

@section('content')

<div class="explore-header">
    <span class="explore-title">탐색</span>
    <a href="{{ url('/diary/create') }}" class="btn-write">➕ 일기 쓰기</a>
</div>

<div class="search-bar">
    <span class="search-icon">🔍</span>
    <input class="search-input" type="text" placeholder="검색">
</div>

<div class="filter-tabs">
    <button class="filter-tab active" onclick="switchTab(this,'grid')">그리드</button>
    <button class="filter-tab" onclick="switchTab(this,'list')">목록</button>
</div>

<!-- 그리드 뷰 -->
<div id="grid-view">
    @php
    $gridItems = [
        ['emoji'=>'🐱','likes'=>1284,'comments'=>47,'wide'=>true,'tall'=>false,'bg'=>'linear-gradient(135deg,#FFF0F5,#FFE4E8)'],
        ['emoji'=>'😺','likes'=>892,'comments'=>23,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#FFF8F0,#FFE4D0)'],
        ['emoji'=>'🐈','likes'=>2104,'comments'=>61,'wide'=>false,'tall'=>true,'bg'=>'linear-gradient(135deg,#F0FFF4,#E4FFE8)'],
        ['emoji'=>'🐾','likes'=>654,'comments'=>18,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#F0F4FF,#E4EAFF)'],
        ['emoji'=>'😸','likes'=>1543,'comments'=>82,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#FFF0F0,#FFE4E4)'],
        ['emoji'=>'🙀','likes'=>443,'comments'=>12,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#FFFFF0,#FFFFE4)'],
        ['emoji'=>'🐈‍⬛','likes'=>987,'comments'=>34,'wide'=>true,'tall'=>false,'bg'=>'linear-gradient(135deg,#F5F0FF,#EAE4FF)'],
        ['emoji'=>'😹','likes'=>2341,'comments'=>95,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#FFF5F0,#FFE8E4)'],
        ['emoji'=>'😻','likes'=>1102,'comments'=>57,'wide'=>false,'tall'=>false,'bg'=>'linear-gradient(135deg,#F0FFF8,#E4FFE8)'],
    ];
    @endphp

    <div class="grid-3">
        @foreach($gridItems as $item)
        <div class="grid-item {{ $item['wide'] ? 'wide' : '' }} {{ $item['tall'] ? 'tall' : '' }}"
             style="background:{{ $item['bg'] }}">
            <div class="grid-emoji">{{ $item['emoji'] }}</div>
            <div class="grid-overlay">
                <span class="ov-stat">❤️ {{ number_format($item['likes']) }}</span>
                <span class="ov-stat">💬 {{ $item['comments'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- 리스트 뷰 -->
<div id="list-view" class="list-view">
    @php
    $posts = [
        ['emoji'=>'🐱','user'=>'나비맘','time'=>'3시간 전','cat'=>'🐱','likes'=>'1,284','caption'=>'오늘 나비가 창가에서 햇살을 받으며 낮잠을 자고 있었어요. 세상 평화로운 표정 🌞','tags'=>'#냥스타그램 #고양이 #묘생일기'],
        ['emoji'=>'😺','user'=>'모찌냥이','time'=>'어제','cat'=>'😺','likes'=>'2,104','caption'=>'모찌의 최애 간식 타임🍗 언제 봐도 먹는 모습이 제일 귀여워요','tags'=>'#모찌 #간식냥 #묘생일기'],
        ['emoji'=>'🐈','user'=>'루시집사','time'=>'5시간 전','cat'=>'🐈','likes'=>'892','caption'=>'루시가 또 박스에 들어가서 안 나오네요... 어쩜 이렇게 귀여울까요 📦','tags'=>'#루시 #박스고양이 #묘생일기'],
    ];
    @endphp

    @foreach($posts as $post)
    <div class="diary-card">
        <div class="dc-header">
            <a href="#" class="dc-author">
                <div class="dc-avatar">{{ $post['emoji'] }}</div>
                <div>
                    <div class="dc-name">{{ $post['user'] }}</div>
                    <div class="dc-time">{{ $post['time'] }}</div>
                </div>
            </a>
            <button style="background:none;border:none;cursor:pointer;font-size:1.2rem">···</button>
        </div>
        <div class="dc-img">{{ $post['cat'] }}</div>
        <div class="dc-actions">
            <button class="dc-act" onclick="this.textContent=this.textContent==='🤍'?'❤️':'🤍'">🤍</button>
            <button class="dc-act">💬</button>
            <button class="dc-act">📤</button>
            <button class="dc-act dc-save">🔖</button>
        </div>
        <div class="dc-likes">좋아요 {{ $post['likes'] }}개</div>
        <div class="dc-caption">
            <span class="dc-cap-user">{{ $post['user'] }}</span>
            {{ $post['caption'] }}
            <span class="dc-tags"> {{ $post['tags'] }}</span>
        </div>
        <div class="dc-date">{{ $post['time'] }}</div>
    </div>
    @endforeach
</div>

@endsection

@section('rightbar')
<div class="section-label" style="color:#8E8E8E;font-weight:600;font-size:0.8rem;margin-bottom:14px;">인기 태그</div>
@foreach(['#묘생일기','#냥스타그램','#고양이','#냥이일상','#집사생활','#캣스타그램'] as $tag)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
    <div style="width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,#FFF0F5,#FFE4E8);display:flex;align-items:center;justify-content:center;font-size:1.3rem;border:1px solid #DBDBDB;">🏷️</div>
    <div>
        <div style="font-size:0.82rem;font-weight:600;">{{ $tag }}</div>
        <div style="font-size:0.72rem;color:#8E8E8E;">{{ rand(500,5000) }}개 게시물</div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
function switchTab(el, view) {
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('grid-view').style.display = view==='grid' ? 'block' : 'none';
    document.getElementById('list-view').style.display = view==='list' ? 'flex' : 'none';
}
</script>
@endsection
