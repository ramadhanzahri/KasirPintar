<?php
helper('app_setting');

$storeName        = app_setting('store_name', 'Nama Toko');
$storeLogo        = app_setting('store_logo', '');
$storeFavicon     = app_setting('store_favicon', '');
$storeDescription = app_setting('store_description', 'Sistem Kasir Pintar');
$footerText       = app_setting('footer_text', 'Powered by Kelompok 6');

$logoSrc = ! empty($storeLogo)
    ? base_url($storeLogo)
    : base_url('assets/img/logo_k.png');

$faviconPath = ! empty($storeFavicon)
    ? $storeFavicon
    : (! empty($storeLogo) ? $storeLogo : 'assets/img/logo_k.png');

$faviconSrc = base_url($faviconPath);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= esc($storeName) ?></title>

    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #020617;
            --bg-soft: #07111f;
            --card: rgba(255, 255, 255, .055);
            --card-strong: rgba(255, 255, 255, .08);
            --border: rgba(255, 255, 255, .10);
            --text: #ffffff;
            --muted: #94a3b8;
            --muted-2: #64748b;
            --sky: #0ea5e9;
            --purple: #7c3aed;
            --rose: #f43f5e;
            --emerald: #22c55e;
            --gradient: linear-gradient(135deg, #0ea5e9, #7c3aed);
            --shadow: 0 24px 80px rgba(0, 0, 0, .42);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, .22), transparent 24rem),
                radial-gradient(circle at top right, rgba(124, 58, 237, .20), transparent 24rem),
                linear-gradient(135deg, #020617 0%, #07111f 48%, #020617 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .9), rgba(0, 0, 0, .18));
            z-index: 0;
        }

        button,
        input {
            -webkit-tap-highlight-color: transparent;
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
            position: relative;
            z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 13px;
            margin-bottom: 24px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            border-radius: 22px;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .10);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(14, 165, 233, .16);
            flex-shrink: 0;
        }

        .brand-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .logo-fallback {
            display: none;
            width: 48px;
            height: 48px;
            border-radius: 18px;
            background: var(--gradient);
            align-items: center;
            justify-content: center;
            color: white;
        }

        .brand-info {
            min-width: 0;
        }

        .brand-info h1 {
            font-size: 22px;
            font-weight: 900;
            color: #fff;
            letter-spacing: -.03em;
            line-height: 1.15;
        }

        .brand-info p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .card {
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, .045);
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 32px;
            padding: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .card::before {
            content: '';
            position: absolute;
            right: -70px;
            top: -80px;
            width: 190px;
            height: 190px;
            background: rgba(14, 165, 233, .22);
            filter: blur(42px);
            border-radius: 50%;
        }

        .card::after {
            content: '';
            position: absolute;
            left: -90px;
            bottom: -90px;
            width: 220px;
            height: 220px;
            background: rgba(124, 58, 237, .16);
            filter: blur(44px);
            border-radius: 50%;
        }

        .card-inner {
            position: relative;
            z-index: 1;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(14, 165, 233, .10);
            border: 1px solid rgba(14, 165, 233, .22);
            color: #7dd3fc;
            font-size: 11px;
            font-weight: 900;
            margin-bottom: 16px;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--emerald);
            box-shadow: 0 0 0 5px rgba(34, 197, 94, .10);
        }

        .card-header-text {
            margin-bottom: 24px;
        }

        .card-header-text h2 {
            font-size: 25px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -.04em;
            color: #fff;
        }

        .card-header-text p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 8px;
            line-height: 1.55;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: rgba(244, 63, 94, .10);
            border: 1px solid rgba(244, 63, 94, .22);
            color: #fda4af;
            border-radius: 18px;
            padding: 13px 14px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .alert i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 900;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 9px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: var(--muted-2);
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            background: rgba(2, 6, 23, .56);
            border: 1px solid rgba(255, 255, 255, .10);
            color: #fff;
            border-radius: 18px;
            padding: 14px 45px 14px 43px;
            font-size: 14px;
            outline: none;
            transition: .2s ease;
        }

        .input-wrap input::placeholder {
            color: #475569;
        }

        .input-wrap input:focus {
            border-color: rgba(14, 165, 233, .55);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, .10);
        }

        .toggle-pw {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: var(--muted-2);
            cursor: pointer;
            transition: .2s ease;
        }

        .toggle-pw:hover {
            color: #fff;
        }

        .btn-submit {
            width: 100%;
            border: 0;
            border-radius: 21px;
            background: var(--gradient);
            color: #fff;
            padding: 15px 16px;
            font-size: 14px;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 20px 50px rgba(14, 165, 233, .25), 0 10px 34px rgba(0, 0, 0, .50);
            transition: transform .15s ease, opacity .15s ease;
            margin-top: 8px;
        }

        .btn-submit:hover {
            opacity: .94;
        }

        .btn-submit:active {
            transform: scale(.98);
        }

        .security-note {
            margin-top: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .035);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .security-note i {
            color: #7dd3fc;
            margin-top: 2px;
        }

        .security-note p {
            font-size: 12px;
            line-height: 1.55;
            color: var(--muted);
        }

        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: var(--muted-2);
            line-height: 1.6;
        }

        .login-footer strong {
            color: #cbd5e1;
        }

        @media (max-width: 420px) {
            .card {
                padding: 24px 20px;
                border-radius: 28px;
            }

            .brand-info h1 {
                font-size: 20px;
            }

            .card-header-text h2 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        <div class="brand">
            <div class="brand-logo">
                <img src="<?= esc($logoSrc) ?>"
                    alt="Logo <?= esc($storeName) ?>"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <div class="logo-fallback">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>

            <div class="brand-info">
                <h1><?= esc($storeName) ?></h1>
                <p><?= esc($storeDescription ?: 'Sistem Pemesanan QR Code') ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-inner">
                <div class="badge">
                    <span class="badge-dot"></span>
                    Role Access
                </div>

                <div class="card-header-text">
                    <h2>Masuk ke Akun</h2>
                    <p>Silakan login menggunakan username dan password yang sudah terdaftar.</p>
                </div>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span><?= esc(session()->getFlashdata('msg')) ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="<?= esc(old('username')) ?>"
                                placeholder="Masukkan username"
                                required
                                autofocus
                                autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password">
                            <i class="fa-solid fa-eye toggle-pw" id="togglePw"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        Masuk Dashboard
                    </button>
                </form>

                <div class="security-note">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p>
                        Halaman ini hanya untuk superadmin dan kasir yang memiliki akses resmi.
                    </p>
                </div>
            </div>
        </div>

        <p class="login-footer">
            &copy; <?= date('Y') ?> <strong><?= esc($storeName) ?></strong>. All rights reserved.<br>
            <?= esc($footerText) ?>
        </p>

    </div>

    <script>
        const togglePw = document.getElementById('togglePw');
        const pwInput = document.getElementById('password');

        if (togglePw && pwInput) {
            togglePw.addEventListener('click', () => {
                const isHidden = pwInput.type === 'password';

                pwInput.type = isHidden ? 'text' : 'password';

                togglePw.classList.toggle('fa-eye', !isHidden);
                togglePw.classList.toggle('fa-eye-slash', isHidden);
            });
        }
    </script>

</body>

</html>