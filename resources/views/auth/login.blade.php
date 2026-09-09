<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - UD Aneka Rupa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #F97316;
            --primary-dark: #EA580C;
            --light-bg: #FFF7ED;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--light-bg) 0%, #FEF3C7 50%, var(--light-bg) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(249,115,22,0.10), transparent 70%);
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(251,191,36,0.12), transparent 70%);
            border-radius: 50%;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 44px 40px;
            box-shadow: 0 20px 60px rgba(249,115,22,0.15);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
        }
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .login-logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--primary), #FBBF24);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(249,115,22,0.35);
        }
        .form-control {
            border: 2px solid #E7E5E4;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all .3s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        }
        .input-group-text {
            background: var(--light-bg);
            border: 2px solid #E7E5E4;
            border-right: 0;
            border-radius: 10px 0 0 10px;
            color: var(--primary);
        }
        .input-group .form-control { border-left: 0; border-radius: 0 10px 10px 0; }
        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 13px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            transition: all .3s;
            box-shadow: 0 6px 20px rgba(249,115,22,0.35);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(249,115,22,0.45);
            color: white;
        }
        .pass-toggle {
            cursor: pointer;
            border: 2px solid #E7E5E4;
            border-left: 0;
            border-radius: 0 10px 10px 0;
            background: var(--light-bg);
            color: var(--primary);
            padding: 0 14px;
        }
        .back-link { color: var(--primary); text-decoration:none; font-size:.85rem; font-weight:600; }
        .back-link:hover { text-decoration:underline; }
        label { font-weight:600; font-size:.875rem; color:#292524; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="login-logo">
            <i class="fa-solid fa-cookie-bite"></i>
        </div>
        <h4 class="fw-800 mb-1">Admin Panel</h4>
        <p class="text-muted small mb-0">UD Aneka Rupa — Sistem Informasi</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger rounded-3 small py-2">
        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label mb-1">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text"
                       name="username"
                       class="form-control"
                       placeholder="Masukkan username"
                       value="{{ old('username') }}"
                       autocomplete="username"
                       required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label mb-1">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password"
                       name="password"
                       id="passwordInput"
                       class="form-control"
                       placeholder="Masukkan password"
                       autocomplete="current-password"
                       required>
                <span class="pass-toggle" onclick="togglePass()">
                    <i class="fa-solid fa-eye" id="passIcon"></i>
                </span>
            </div>
            <div class="mt-1 text-muted" style="font-size:.77rem;">
                <i class="fa-solid fa-circle-info me-1"></i>
                Demo: username = <code>admin</code>, password = <code>admin123</code>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Admin Panel
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Website
        </a>
    </div>
</div>

<script>
function togglePass() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('passIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}
</script>
</body>
</html>
