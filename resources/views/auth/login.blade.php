<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>묘생일기 · 로그인</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --border: #DBDBDB;
            --text:   #262626;
            --muted:  #8E8E8E;
            --blue:   #0095F6;
            --bg:     #FAFAFA;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Noto Sans KR',-apple-system,sans-serif; background:var(--bg); color:var(--text); font-size:14px; display:flex; flex-direction:column; min-height:100vh; align-items:center; justify-content:center; padding:20px; }

        .auth-layout { display:flex; gap:32px; align-items:center; max-width:900px; width:100%; }

        /* 왼쪽 폰 목업 */
        .phone-mock {
            width: 280px; flex-shrink:0;
        }
        .phone-mock img { width:100%; }
        .phone-frame {
            width:280px; height:520px;
            border: 2px solid #DBDBDB;
            border-radius: 36px;
            background: white;
            position:relative;
            overflow:hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            display:flex; flex-direction:column;
        }
        .phone-notch {
            width:80px; height:22px;
            background:#111; border-radius:0 0 16px 16px;
            margin:0 auto;
            position:relative; z-index:2;
        }
        .phone-screen {
            flex:1; padding:12px;
            background:linear-gradient(160deg,#FFF0F5 0%,#FFF8F0 50%,#F0F8FF 100%);
            display:flex; flex-direction:column; gap:10px;
        }
        .phone-post {
            background:white; border-radius:12px;
            padding:10px; border:1px solid #eee;
        }
        .phone-post-header { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
        .phone-avatar { width:26px; height:26px; border-radius:50%; background:linear-gradient(45deg,#E1306C,#FCAF45); display:flex; align-items:center; justify-content:center; font-size:0.8rem; }
        .phone-uname { font-size:0.72rem; font-weight:600; }
        .phone-img { width:100%; height:80px; background:linear-gradient(135deg,#FFF0F5,#FFE4E8); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:2.5rem; }
        .phone-actions { display:flex; gap:8px; margin-top:6px; font-size:0.85rem; }
        .phone-likes { font-size:0.68rem; font-weight:600; margin-top:4px; }
        .phone-caption { font-size:0.65rem; color:#555; margin-top:2px; }

        /* 우측 로그인 박스 */
        .auth-right { width:100%; max-width:350px; }

        .auth-box {
            background:white; border:1px solid var(--border);
            border-radius:2px; padding:40px 40px 28px;
            margin-bottom:10px;
        }

        .auth-logo {
            font-family:'Dancing Script',cursive; font-size:2.5rem; font-weight:700;
            text-align:center; display:block; margin-bottom:28px;
            background:linear-gradient(45deg,#E1306C,#F77737,#FCAF45);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }

        .form-field {
            width:100%; padding:10px 12px;
            background:#FAFAFA; border:1px solid var(--border);
            border-radius:4px; font-family:inherit; font-size:0.78rem;
            outline:none; margin-bottom:8px; color:var(--text);
            transition:border-color 0.15s;
        }
        .form-field:focus { border-color:#A8A8A8; background:white; }
        .form-field::placeholder { color:var(--muted); }

        .btn-login {
            width:100%; padding:8px;
            background:var(--blue); color:white;
            border:none; border-radius:8px;
            font-size:0.875rem; font-weight:600;
            cursor:pointer; margin-top:4px;
            opacity:0.7; transition:opacity 0.15s;
        }
        .btn-login:hover { opacity:1; }
        .btn-login.active { opacity:1; }

        .or-divider {
            display:flex; align-items:center; gap:14px;
            margin:18px 0; color:var(--muted); font-size:0.8rem; font-weight:600;
        }
        .or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background:var(--border); }

        .btn-kakao {
            width:100%; padding:9px;
            background:#FEE500; color:#191919;
            border:none; border-radius:8px;
            font-size:0.82rem; font-weight:600;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
        }

        .forgot { display:block; text-align:center; margin-top:16px; color:var(--muted); font-size:0.78rem; text-decoration:none; }
        .forgot:hover { color:var(--text); }

        .auth-box2 {
            background:white; border:1px solid var(--border);
            border-radius:2px; padding:20px 40px;
            text-align:center; font-size:0.875rem;
        }
        .auth-box2 a { color:var(--blue); font-weight:600; text-decoration:none; }

        .app-badges { display:flex; gap:8px; justify-content:center; margin-top:20px; }
        .badge-btn {
            padding:7px 14px;
            border:1px solid var(--border); border-radius:6px;
            font-size:0.72rem; color:var(--text);
            text-decoration:none; display:flex; align-items:center; gap:6px;
        }

        @media(max-width:700px) { .phone-mock { display:none; } .auth-layout { justify-content:center; } }
    </style>
</head>
<body>

<div class="auth-layout">

    <!-- 폰 목업 -->
    <div class="phone-mock">
        <div class="phone-frame">
            <div class="phone-notch"></div>
            <div class="phone-screen">
                @foreach([
                    ['🐱','나비맘','🐱','냥스타그램','1,284'],
                    ['🐈','루시집사','📦','박스고양이','892'],
                    ['😺','모찌냥','🍗','간식타임','2,104'],
                ] as $p)
                <div class="phone-post">
                    <div class="phone-post-header">
                        <div class="phone-avatar">{{ $p[0] }}</div>
                        <span class="phone-uname">{{ $p[1] }}</span>
                    </div>
                    <div class="phone-img">{{ $p[2] }}</div>
                    <div class="phone-actions">🤍 💬 📤</div>
                    <div class="phone-likes">좋아요 {{ $p[4] }}개</div>
                    <div class="phone-caption">{{ $p[3] }} #묘생일기</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 로그인 폼 -->
    <div class="auth-right">
        <div class="auth-box">
            <a href="{{ url('/') }}" class="auth-logo">묘생일기</a>

            @if($errors->any())
                <div style="background:#ffe0e0; color:#c0392b; padding:10px 12px; border-radius:4px; font-size:0.82rem; margin-bottom:12px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <input type="text" name="email" class="form-field" placeholder="전화번호, 사용자 이름 또는 이메일" value="{{ old('email') }}" required>
                <input type="password" name="password" class="form-field" placeholder="비밀번호" required>
                <button type="submit" class="btn-login active">로그인</button>
            </form>

            <div class="or-divider">또는</div>

            <button class="btn-kakao">
                <span style="font-size:1.1rem">💛</span> 카카오로 로그인
            </button>

            <a href="#" class="forgot">비밀번호를 잊으셨나요?</a>
        </div>

        <div class="auth-box2">
            계정이 없으신가요? <a href="{{ url('/register') }}">가입하기</a>
        </div>

        <div style="text-align:center; margin-top:20px;">
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:12px;">앱을 다운로드하세요.</p>
            <div class="app-badges">
                <a href="#" class="badge-btn">📱 App Store</a>
                <a href="#" class="badge-btn">🤖 Google Play</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
