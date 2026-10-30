<?php
helper('app_setting');

$role = session()->get('role');
$nama = session()->get('nama_lengkap') ?? 'User';

$storeName    = app_setting('store_name', 'Nama Toko');
$storeLogo    = app_setting('store_logo', '');
$storeFavicon = app_setting('store_favicon', '');
$footerText   = app_setting('footer_text', 'Powered by Kelompok 6');

$logoSrc = ! empty($storeLogo)
    ? base_url($storeLogo)
    : base_url('assets/img/logo.png');

$faviconPath = ! empty($storeFavicon)
    ? $storeFavicon
    : (! empty($storeLogo) ? $storeLogo : 'assets/img/logo.png');

$faviconSrc = base_url($faviconPath);

$isAdmin = $role === 'superadmin';

$isActive = static function (string $path) {
    $current = trim(uri_string(), '/');
    $target  = trim($path, '/');

    if ($target === '') {
        return $current === '';
    }

    return $current === $target || str_starts_with($current, $target . '/');
};
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? ($storeName . ' Admin')) ?></title>

    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">

    <script src="<?= base_url('assets/js/tailwind.js') ?>"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">



    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        dark: {
                            950: '#020617',
                            900: '#06111f',
                            850: '#091827',
                            800: '#0f2135',
                            700: '#16324f'
                        },
                        neon: {
                            blue: '#008CFF',
                            cyan: '#00E5FF',
                            purple: '#7C3AED',
                            green: '#22C55E',
                            orange: '#FB923C'
                        }
                    },
                    boxShadow: {
                        glow: '0 0 35px rgba(0, 140, 255, 0.18)',
                        soft: '0 18px 60px rgba(0, 0, 0, 0.35)'
                    }
                }
            }
        }
    </script>

    <style>
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(56, 189, 248, .35) transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .18), transparent 28rem),
                radial-gradient(circle at top right, rgba(124, 58, 237, .16), transparent 26rem),
                linear-gradient(135deg, #020617 0%, #06111f 45%, #020617 100%);
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .85), rgba(0, 0, 0, .18));
            z-index: 0;
        }

        .glass-card {
            background: linear-gradient(180deg, rgba(15, 33, 53, .88), rgba(6, 17, 31, .88));
            border: 1px solid rgba(148, 163, 184, .16);
            box-shadow: 0 18px 60px rgba(0, 0, 0, .28);
        }

        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(56, 189, 248, .25);
            border-radius: 999px;
        }

        .nav-link {
            position: relative;
            transition: all .2s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0;
            transform: translateY(-50%);
            border-radius: 0 999px 999px 0;
            background: linear-gradient(180deg, #00E5FF, #7C3AED);
            transition: height .2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(90deg, rgba(0, 140, 255, .18), rgba(124, 58, 237, .18));
            color: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(56, 189, 248, .16);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            height: 58%;
        }

        .nav-link.active .nav-icon {
            background: linear-gradient(135deg, #008CFF, #7C3AED);
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(37, 99, 235, .35);
        }

        .nav-link-soon {
            opacity: .72;
        }

        .nav-link-soon:hover {
            opacity: 1;
        }

        #sidebar {
            transition: transform .25s cubic-bezier(.4, 0, .2, 1);
        }

        #overlay {
            transition: opacity .25s ease;
        }

        .topbar-blur {
            background: rgba(2, 6, 23, .72);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
    </style>
</head>

<body class="text-slate-100 antialiased">

    <div id="overlay" class="fixed inset-0 bg-black/70 z-30 hidden opacity-0 lg:hidden" onclick="closeSidebar()"></div>

    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen w-72 bg-dark-950/95 border-r border-white/10 z-40 flex flex-col sidebar-scrollbar overflow-y-auto -translate-x-full lg:translate-x-0">

        <div class="px-5 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="relative w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden bg-white/5 border border-white/10 shadow-glow">
                    <img src="<?= esc($logoSrc) ?>"
                        alt="Logo <?= esc($storeName) ?>"
                        class="w-12 h-12 object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">

                    <div class="hidden flex w-12 h-12 rounded-2xl bg-gradient-to-br from-neon-blue via-neon-cyan to-neon-purple items-center justify-center">
                        <i class="fa-solid fa-cash-register text-white text-xl"></i>
                    </div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-white font-extrabold text-base tracking-wide leading-tight truncate">
                        <?= esc($storeName) ?>
                    </h1>
                    <p class="text-slate-400 text-xs mt-1">
                        <?= esc(ucfirst($role ?? 'User')) ?> Dashboard
                    </p>
                </div>

                <button onclick="closeSidebar()" class="ml-auto lg:hidden w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <nav class="flex-1 px-4 py-5 space-y-6">

            <?php if ($isAdmin): ?>

                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Menu Utama
                    </p>

                    <div class="space-y-1">
                        <a href="<?= base_url('admin/dashboard') ?>"
                            class="nav-link <?= $isActive('admin/dashboard') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-house"></i>
                            </span>
                            <span>Dashboard</span>
                        </a>

                        <a href="<?= base_url('admin/menu') ?>"
                            class="nav-link <?= $isActive('admin/menu') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-bowl-food"></i>
                            </span>
                            <span>Menu</span>
                        </a>

                        <a href="<?= base_url('admin/riwayat') ?>"
                            class="nav-link <?= $isActive('admin/riwayat') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-receipt"></i>
                            </span>
                            <span>Pesanan</span>
                        </a>

                        <a href="<?= base_url('admin/riwayat-stok') ?>"
                            class="nav-link <?= $isActive('admin/riwayat-stok') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-box"></i>
                            </span>
                            <span>Riwayat Stok</span>
                        </a>

                        <a href="<?= base_url('admin/users') ?>"
                            class="nav-link <?= $isActive('admin/users') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-users-gear"></i>
                            </span>
                            <span>Kelola User</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Pengaturan
                    </p>

                    <div class="space-y-1">
                        <a href="<?= base_url('admin/pengaturan/akun') ?>"
                            class="nav-link <?= $isActive('admin/pengaturan/akun') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-user-gear"></i>
                            </span>
                            <span>Profil Saya</span>
                        </a>

                        <a href="<?= base_url('admin/pengaturan') ?>"
                            class="nav-link <?= ($isActive('admin/pengaturan') && !$isActive('admin/pengaturan/akun')) ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-store"></i>
                            </span>
                            <span>Pengaturan Toko</span>
                        </a>
                    </div>
                </div>

            <?php else: ?>

                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Kasir
                    </p>

                    <div class="space-y-1">
                        <a href="<?= base_url('kasir/pos') ?>"
                            class="nav-link <?= ($isActive('kasir/pos') || $isActive('kasir/dashboard')) ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-cash-register"></i>
                            </span>
                            <span>Pesanan</span>
                        </a>

                        <a href="<?= base_url('kasir/riwayat') ?>"
                            class="nav-link <?= $isActive('kasir/riwayat') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </span>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Pengaturan
                    </p>

                    <div class="space-y-1">
                        <a href="<?= base_url('kasir/pengaturan') ?>"
                            class="nav-link <?= $isActive('kasir/pengaturan') ? 'active' : '' ?> flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300">
                            <span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 transition">
                                <i class="fa-solid fa-gear"></i>
                            </span>
                            <span>Pengaturan Akun</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </nav>
    </aside>

    <div class="relative z-10 lg:ml-72 min-h-screen flex flex-col">

        <header class="sticky top-0 z-20 topbar-blur border-b border-white/10">
            <div class="px-4 lg:px-7 py-4 flex items-center justify-between gap-4">

                <div class="flex items-center gap-4 min-w-0">
                    <button onclick="openSidebar()"
                        class="lg:hidden w-10 h-10 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center text-slate-300 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="min-w-0">
                        <h2 class="text-xl lg:text-2xl font-extrabold tracking-tight text-white">
                            Halo, <?= esc($nama) ?>! <span class="inline-block">👋</span>
                        </h2>
                        <p class="hidden sm:block text-sm text-slate-400 mt-1">
                            Selamat datang di dashboard <?= $isAdmin ? 'Superadmin' : 'Kasir' ?> <?= esc($storeName) ?>.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">

                    <div class="hidden md:flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/[.03] border border-white/10">
                        <div class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-white"><?= date('d M Y') ?></p>
                            <p class="text-[11px] text-slate-400">Hari ini</p>
                        </div>
                    </div>

                    <div class="relative group">
                        <button type="button"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-2xl bg-white/[.03] hover:bg-white/[.06] border border-white/10 transition">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-blue via-neon-cyan to-neon-purple p-[2px]">
                                <div class="w-full h-full rounded-full bg-dark-950 flex items-center justify-center overflow-hidden">
                                    <img src="<?= esc($logoSrc) ?>"
                                        alt="Avatar"
                                        class="w-8 h-8 object-contain"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">

                                    <i class="hidden fa-solid fa-user text-slate-300 text-sm"></i>
                                </div>
                            </div>

                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-white leading-tight"><?= esc($nama) ?></p>
                                <p class="text-[11px] text-slate-400 capitalize"><?= esc($role ?? 'User') ?></p>
                            </div>

                            <i class="fa-solid fa-chevron-down text-xs text-slate-500 hidden sm:block"></i>
                        </button>

                        <div class="absolute right-0 mt-2 w-52 rounded-2xl bg-dark-900 border border-white/10 shadow-soft opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all overflow-hidden">
                            <div class="px-4 py-3 border-b border-white/10">
                                <p class="text-sm font-semibold text-white"><?= esc($nama) ?></p>
                                <p class="text-xs text-slate-400 capitalize"><?= esc($role ?? 'User') ?></p>
                            </div>

                            <a href="<?= base_url('logout') ?>"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-rose-400 hover:bg-rose-500/10 transition">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                Logout
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1 px-4 lg:px-7 py-6">
            <?= $this->renderSection('content') ?>
        </main>

        <footer class="px-4 lg:px-7 py-5 border-t border-white/10 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs text-slate-500">
                <p>
                    &copy; <?= date('Y') ?> <span class="text-sky-400 font-semibold"><?= esc($storeName) ?></span>. All rights reserved.
                </p>
                <p class="md:text-right">
                    <?= esc($footerText) ?>
                </p>
            </div>
        </footer>
    </div>

    <?php if (! $isAdmin): ?>
        <nav class="fixed bottom-0 left-0 right-0 z-30 bg-dark-950/90 backdrop-blur-lg border-t border-white/10 px-4 py-2 lg:hidden">
            <div class="flex items-center justify-around max-w-md mx-auto">
                <a href="<?= base_url('kasir/pos') ?>" class="flex flex-col items-center gap-1 text-xs text-slate-400 hover:text-neon-cyan transition">
                    <i class="fa-solid fa-cash-register text-lg"></i>
                    <span>POS</span>
                </a>
                <a href="<?= base_url('kasir/riwayat') ?>" class="flex flex-col items-center gap-1 text-xs text-slate-400 hover:text-neon-purple transition">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    <span>Riwayat</span>
                </a>
                <a href="<?= base_url('kasir/pengaturan') ?>" class="flex flex-col items-center gap-1 text-xs text-slate-400 hover:text-neon-orange transition">
                    <i class="fa-solid fa-gear text-lg"></i>
                    <span>Pengaturan</span>
                </a>
            </div>
        </nav>
    <?php endif; ?>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');

            setTimeout(() => {
                overlay.classList.remove('opacity-0');
            }, 10);
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');

            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 250);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>

    <div id="global-toast-container" class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 w-80"></div>

    <script>
        function posAlert(title, msg, type = 'rose') {
            const cont = document.getElementById('global-toast-container');
            const el = document.createElement('div');
            const color = type === 'emerald' ? 'emerald' : 'rose';
            el.className = `relative flex items-start gap-3 rounded-2xl border border-${color}-400/30 bg-slate-900/95 backdrop-blur p-4 shadow-2xl overflow-hidden`;
            el.innerHTML = `
                <div class="w-9 h-9 rounded-xl bg-${color}-500/15 border border-${color}-400/20 flex items-center justify-center text-${color}-300 shrink-0"><i class="fa-solid fa-${type === 'emerald' ? 'circle-check' : 'triangle-exclamation'} text-sm"></i></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-extrabold text-white">${title}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">${msg}</p>
                </div>
                <button type="button" onclick="this.closest('div').remove()" class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-xmark text-xs"></i></button>
                <div class="absolute bottom-0 left-0 h-0.5 bg-${color}-500 w-full animate-[shrink_3s_linear_forwards]"></div>`;
            cont.appendChild(el);
            setTimeout(() => el.remove(), 3200);
        }
    </script>
    <style>@keyframes shrink{from{width:100%}to{width:0}}</style>
</body>

</html>