<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>묘생일기 · 가입하기</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --border:#DBDBDB; --text:#262626; --muted:#8E8E8E; --blue:#0095F6; --bg:#FAFAFA; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Noto Sans KR',-apple-system,sans-serif; background:var(--bg); color:var(--text); font-size:14px; display:flex; flex-direction:column; min-height:100vh; align-items:center; justify-content:center; padding:20px; }

        .auth-box {
            background:white; border:1px solid var(--border);
            border-radius:2px; padding:40px 40px 28px;
            width:100%; max-width:350px; margin-bottom:10px;
        }
        .auth-logo {
            font-family:'Dancing Script',cursive; font-size:2.5rem; font-weight:700;
            text-align:center; display:block; margin-bottom:8px;
            background:linear-gradient(45deg,#E1306C,#F77737,#FCAF45);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        .auth-tagline { text-align:center; font-size:1rem; font-weight:600; color:var(--muted); margin-bottom:20px; line-height:1.5; }
        .form-field {
            width:100%; padding:10px 12px;
            background:#FAFAFA; border:1px solid var(--border);
            border-radius:4px; font-family:inherit; font-size:0.78rem;
            outline:none; margin-bottom:8px; color:var(--text); transition:border-color 0.15s;
        }
        .form-field:focus { border-color:#A8A8A8; background:white; }
        .form-field::placeholder { color:var(--muted); }
        .btn-signup {
            width:100%; padding:8px;
            background:var(--blue); color:white;
            border:none; border-radius:8px;
            font-size:0.875rem; font-weight:600;
            cursor:pointer; margin-top:4px;
        }
        .terms { font-size:0.72rem; color:var(--muted); text-align:center; margin-top:16px; line-height:1.6; }
        .terms a { color:var(--text); font-weight:600; text-decoration:none; }
        .or-divider { display:flex; align-items:center; gap:14px; margin:14px 0; color:var(--muted); font-size:0.8rem; font-weight:600; }
        .or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background:var(--border); }
        .btn-kakao {
            width:100%; padding:9px;
            background:#FEE500; color:#191919;
            border:none; border-radius:8px;
            font-size:0.82rem; font-weight:600;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .auth-box2 { background:white; border:1px solid var(--border); border-radius:2px; padding:20px 40px; text-align:center; font-size:0.875rem; width:100%; max-width:350px; }
        .auth-box2 a { color:var(--blue); font-weight:600; text-decoration:none; }
    </style>
</head>
<body>

<div class="auth-box">
    <a href="{{ url('/') }}" class="auth-logo">묘생일기</a>
    <p class="auth-tagline">친구들의 냥이 사진과<br>일상을 확인해보세요.</p>

    @if($errors->any())
        <div style="background:#ffe0e0; color:#c0392b; padding:10px 12px; border-radius:4px; font-size:0.82rem; margin-bottom:12px;">
            {{ $errors->first() }}
        </div>
    @endif

    <button class="btn-kakao" style="margin-bottom:14px;">
        <span style="font-size:1.1rem">💛</span> 카카오로 가입하기
    </button>

    <div class="or-divider">또는</div>

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <input type="email"    name="email"                 class="form-field" placeholder="이메일 주소"   value="{{ old('email') }}"  required>
        <input type="text"     name="name"                  class="form-field" placeholder="성명"          value="{{ old('name') }}"   required>
        <input type="text"     name="username"              class="form-field" placeholder="사용자 이름"   value="{{ old('username') }}" required>
        <input type="password" name="password"              class="form-field" placeholder="비밀번호"                                  required>
        <input type="password" name="password_confirmation" class="form-field" placeholder="비밀번호 확인"                             required>
        <button type="submit" class="btn-signup">가입하기</button>
    </form>

    <div class="terms">
        가입하면 묘생일기의 <a href="#">약관</a>,
        <a href="#">데이터 정책</a> 및 <a href="#">쿠키 정책</a>에
        동의하게 됩니다.
    </div>
</div>

<div class="auth-box2">
    계정이 있으신가요? <a href="{{ url('/login') }}">로그인</a>
</div>

</body>
</html>
