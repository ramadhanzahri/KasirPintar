<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="/assets/img/logo_k.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        dark: { 950: '#020617', 900: '#06111f', 850: '#091827', 800: '#0f2135', 700: '#16324f' },
                        neon: { blue: '#008CFF', cyan: '#00E5FF', purple: '#7C3AED', green: '#22C55E', orange: '#FB923C' }
                    },
                    boxShadow: { glow: '0 0 35px rgba(0,140,255,.18)', soft: '0 18px 60px rgba(0,0,0,.35)' }
                }
            }
        }
    </script>
    <style>
        *{scrollbar-width:thin;scrollbar-color:rgba(56,189,248,.35) transparent}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:radial-gradient(circle at top left,rgba(37,99,235,.18),transparent 28rem),radial-gradient(circle at top right,rgba(124,58,237,.16),transparent 26rem),linear-gradient(135deg,#020617 0%,#06111f 45%,#020617 100%);min-height:100vh}
        body::before{content:'';position:fixed;inset:0;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;mask-image:linear-gradient(to bottom,rgba(0,0,0,.85),rgba(0,0,0,.18));z-index:0}
        .glass-card{background:linear-gradient(180deg,rgba(15,33,53,.88),rgba(6,17,31,.88));border:1px solid rgba(148,163,184,.16);box-shadow:0 18px 60px rgba(0,0,0,.28)}
        .sidebar-scrollbar::-webkit-scrollbar{width:4px}.sidebar-scrollbar::-webkit-scrollbar-thumb{background:rgba(56,189,248,.25);border-radius:999px}
        .nav-link{position:relative;transition:all .2s ease}
        .nav-link::before{content:'';position:absolute;left:0;top:50%;width:3px;height:0;transform:translateY(-50%);border-radius:0 999px 999px 0;background:linear-gradient(180deg,#00E5FF,#7C3AED);transition:height .2s ease}
        .nav-link:hover,.nav-link.active{background:linear-gradient(90deg,rgba(0,140,255,.18),rgba(124,58,237,.18));color:#fff;box-shadow:inset 0 0 0 1px rgba(56,189,248,.16)}
        .nav-link:hover::before,.nav-link.active::before{height:58%}
        .nav-link.active .nav-icon{background:linear-gradient(135deg,#008CFF,#7C3AED);color:#fff;box-shadow:0 8px 24px rgba(37,99,235,.35)}
        #sidebar{transition:transform .25s cubic-bezier(.4,0,.2,1)}#overlay{transition:opacity .25s ease}
        .topbar-blur{background:rgba(2,6,23,.72);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px)}
    </style>
</head>
<body class="text-slate-100 antialiased">
    <div id="overlay" class="fixed inset-0 bg-black/70 z-30 hidden opacity-0 lg:hidden" onclick="closeSidebar()"></div>
    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-72 bg-dark-950/95 border-r border-white/10 z-40 flex flex-col sidebar-scrollbar overflow-y-auto -translate-x-full lg:translate-x-0">
        <div class="px-5 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden bg-white/5 border border-white/10 shadow-glow">
                    <img src="/assets/img/logo_k.png" alt="ARZANA" class="w-12 h-12 object-contain" onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden')">
                    <div class="hidden w-12 h-12 rounded-2xl bg-gradient-to-br from-neon-blue via-neon-cyan to-neon-purple items-center justify-center"><i class="fa-solid fa-cash-register text-white text-xl"></i></div>
                </div>
                <div class="min-w-0">
                    <h1 class="text-white font-extrabold text-base tracking-wide leading-tight">Nama Toko</h1>
                    <p class="text-slate-400 text-xs mt-1">Frontend Demo</p>
                </div>
                <button onclick="closeSidebar()" class="ml-auto lg:hidden w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <nav class="flex-1 px-4 py-5 space-y-6">
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Menu Utama (Admin)</p>
                <div class="space-y-1">
                    <a href="/admin/dashboard" data-path="admin/dashboard" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-house"></i></span><span>Dashboard</span></a>
                    <a href="/admin/menu" data-path="admin/menu" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-bowl-food"></i></span><span>Kelola Menu</span></a>
                    <a href="/admin/riwayat" data-path="admin/riwayat" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-receipt"></i></span><span>Riwayat Transaksi</span></a>
                    <a href="/admin/riwayat-stok" data-path="admin/riwayat-stok" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-box"></i></span><span>Riwayat Stok</span></a>
                    <a href="/admin/users" data-path="admin/users" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-users-gear"></i></span><span>Kelola Akun</span></a>
                    <a href="/admin/pengaturan" data-path="admin/pengaturan" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-store"></i></span><span>Pengaturan Toko</span></a>
                </div>
            </div>
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Kasir</p>
                <div class="space-y-1">
                    <a href="/kasir/pos" data-path="kasir/pos" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-cash-register"></i></span><span>Pesanan</span></a>
                    <a href="/kasir/riwayat" data-path="kasir/riwayat" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-clock-rotate-left"></i></span><span>Riwayat Transaksi</span></a>
                    <a href="/kasir/pengaturan" data-path="kasir/pengaturan" class="nav-link flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-slate-300"><span class="nav-icon w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-solid fa-gear"></i></span><span>Data Profile</span></a>
                </div>
            </div>
            <div class="px-3 space-y-2">
                <?php if (session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-rose-300 hover:bg-rose-500/10 border border-transparent hover:border-rose-400/20 transition"><i class="fa-solid fa-right-from-bracket w-9 h-9 rounded-xl bg-rose-500/10 flex items-center justify-center"></i><span>Logout (<?= esc(session()->get('username')) ?>)</span></a>
                <?php else: ?>
                    <a href="<?= base_url('auth') ?>" class="flex items-center gap-3 px-3 py-3 rounded-2xl text-sm text-sky-300 hover:bg-sky-500/10 border border-transparent hover:border-sky-400/20 transition"><i class="fa-solid fa-right-to-bracket w-9 h-9 rounded-xl bg-sky-500/10 flex items-center justify-center"></i><span>Login</span></a>
                <?php endif; ?>
            </div>
        </nav>
    </aside>
    <div class="relative z-10 lg:ml-72 min-h-screen flex flex-col">
        <header class="sticky top-0 z-20 topbar-blur border-b border-white/10">
            <div class="px-4 lg:px-7 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <button onclick="openSidebar()" class="lg:hidden w-10 h-10 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center text-slate-300"><i class="fa-solid fa-bars"></i></button>
                    <div class="min-w-0">
                        <h2 class="text-xl lg:text-2xl font-extrabold tracking-tight text-white">Halo, Demo User! 👋</h2>
                        <p class="hidden sm:block text-sm text-slate-400 mt-1">Mode front-end only — data dummy tanpa backend.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="hidden md:flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/[.03] border border-white/10">
                        <div class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-slate-300"><i class="fa-regular fa-calendar"></i></div>
                        <div><p class="text-xs font-semibold text-white" id="header-date">08 Oct 2026</p><p class="text-[11px] text-slate-400">Hari ini</p></div>
                    </div>
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-2xl bg-white/[.03] border border-white/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-blue via-neon-cyan to-neon-purple p-[2px]"><div class="w-full h-full rounded-full bg-dark-950 flex items-center justify-center"><i class="fa-solid fa-user text-slate-300 text-sm"></i></div></div>
                        <div class="hidden sm:block text-left"><p class="text-sm font-semibold text-white leading-tight"><?= esc(session()->get('nama_lengkap') ?? 'Demo User') ?></p><p class="text-[11px] text-slate-400"><?= esc(session()->get('role') ?? 'Frontend') ?></p></div>
                        <?php if (session()->get('isLoggedIn')): ?>
                            <a href="<?= base_url('logout') ?>" class="hidden sm:flex w-8 h-8 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 items-center justify-center" title="Logout"><i class="fa-solid fa-right-from-bracket text-xs"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        <main class="flex-1 px-4 lg:px-7 py-6">
            <?php if (session()->getFlashdata('msg')): ?>
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-check-circle"></i> <?= esc(session()->getFlashdata('msg')) ?>
                </div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
        <footer class="px-4 lg:px-7 py-5 border-t border-white/10 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs text-slate-500">
                <p>&copy; 2026 <span class="text-sky-400 font-semibold">Nama Toko</span>. Frontend dummy.</p>
                <p>Powered by Kelompok 6 — front-end only</p>
            </div>
        </footer>
    </div>
    <script>
        document.getElementById('header-date').textContent = new Date().toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});
        document.querySelectorAll('[data-path]').forEach(a=>{
            const cur = location.pathname.replace(/^\/+|\/+$/g,'');
            const tgt = a.getAttribute('data-path');
            if(cur===tgt || cur.startsWith(tgt+'/')) a.classList.add('active');
        });
        function openSidebar(){const s=document.getElementById('sidebar'),o=document.getElementById('overlay');s.classList.remove('-translate-x-full');o.classList.remove('hidden');setTimeout(()=>o.classList.remove('opacity-0'),10)}
        function closeSidebar(){const s=document.getElementById('sidebar'),o=document.getElementById('overlay');s.classList.add('-translate-x-full');o.classList.add('opacity-0');setTimeout(()=>o.classList.add('hidden'),250)}
        document.addEventListener('keydown',e=>{if(e.key==='Escape')closeSidebar()});
    </script>
</body>
</html>
