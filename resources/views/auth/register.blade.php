<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat-Log · 가입하기</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent:    #f4a7b9;
            --accent-dk: #de8a98;
            --accent-lt: #fdeef2;
            --bg:        #f7f7f7;
            --surface:   #ffffff;
            --border:    #e8e8e8;
            --text:      #333333;
            --muted:     #999999;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans KR', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-wrap {
            width: 100%;
            max-width: 380px;
        }
        .auth-logo {
            display: block;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: -0.5px;
            margin-bottom: 28px;
        }
        .auth-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 36px 32px;
            margin-bottom: 12px;
        }
        .auth-box h2 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            text-align: center;
        }
        .error-box {
            background: #fff0f0;
            color: #c0392b;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 0.82rem;
            margin-bottom: 16px;
            border: 1px solid #fcd5d5;
        }
        .form-field {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            outline: none;
            margin-bottom: 10px;
            color: var(--text);
            transition: border-color 0.15s;
        }
        .form-field:focus {
            border-color: var(--accent);
            background: var(--surface);
        }
        .form-field::placeholder { color: var(--muted); }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: var(--accent-dk); }
        .auth-link-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            font-size: 0.875rem;
            color: var(--muted);
        }
        .auth-link-box a {
            color: var(--accent-dk);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-link-box a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-wrap">
    <a href="{{ url('/') }}" class="auth-logo">Cat-Log</a>

    <div class="auth-box">
        <h2>가입하기</h2>

        @if($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            <input type="text"     name="name"                  class="form-field" placeholder="이름"          value="{{ old('name') }}"  required>
            <input type="email"    name="email"                 class="form-field" placeholder="이메일 주소"   value="{{ old('email') }}" required>
            <input type="password" name="password"              class="form-field" placeholder="비밀번호 (8자 이상)"                       required>
            <input type="password" name="password_confirmation" class="form-field" placeholder="비밀번호 확인"                             required>
            <button type="submit" class="btn-submit">가입하기</button>
        </form>
    </div>

    <div class="auth-link-box">
        계정이 있으신가요? <a href="{{ url('/login') }}">로그인</a>
    </div>
</div>

</body>
</html>
