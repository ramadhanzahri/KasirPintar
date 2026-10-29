<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/10 border border-sky-400/20 text-sky-300 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-users-gear"></i>
                        User Management
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Manajemen Akun
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Kelola akun akses untuk Superadmin dan Kasir yang dapat masuk ke sistem.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/[.04] border border-white/10 p-4 min-w-full sm:min-w-[280px] lg:min-w-[320px]">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-400/20 flex items-center justify-center text-purple-300">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-white">
                                Total Akun
                            </p>
                            <p class="text-2xl font-extrabold text-white mt-1">
                                2
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-white/10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-white">
                    Daftar Akun
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Akun superadmin dan kasir yang terdaftar.
                </p>
            </div>
            <button type="button" id="btnOpenAddUser" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                <i class="fa-solid fa-plus"></i>
                Tambah Akun
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 bg-white/[.025] text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                        <th class="px-5 py-4 whitespace-nowrap">Pengguna</th>
                        <th class="px-5 py-4 whitespace-nowrap">Username</th>
                        <th class="px-5 py-4 whitespace-nowrap">Role</th>
                        <th class="px-5 py-4 whitespace-nowrap">Status</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    <tr class="hover:bg-white/[.025] transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-purple-600 p-[2px]">
                                    <div class="w-full h-full rounded-full bg-slate-950 flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-user text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">Super Admin</p>
                                    <p class="text-xs text-slate-500">ID: 1</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">superadmin</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-purple-300 bg-purple-500/10 border border-purple-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Superadmin
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-emerald-300 bg-emerald-500/10 border border-emerald-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 w-fit">
                                <i class="fa-solid fa-circle"></i> Aktif
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-white/[.02] border border-white/5 px-3 py-1.5 rounded-xl cursor-not-allowed">
                                <i class="fa-solid fa-lock text-[10px]"></i> Terkunci
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-white/[.025] transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-purple-600 p-[2px]">
                                    <div class="w-full h-full rounded-full bg-slate-950 flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-user text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">Kasir Utama</p>
                                    <p class="text-xs text-slate-500">ID: 2</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">kasir01</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-sky-300 bg-sky-500/10 border border-sky-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Kasir
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-emerald-300 bg-emerald-500/10 border border-emerald-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 w-fit">
                                <i class="fa-solid fa-circle"></i> Aktif
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-400/20 text-sky-300 hover:bg-sky-500 hover:text-white transition inline-flex items-center justify-center">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-400/20 text-amber-300 hover:bg-amber-500 hover:text-white transition inline-flex items-center justify-center">
                                    <i class="fa-solid fa-toggle-on text-lg"></i>
                                </button>
                                <button type="button" class="w-9 h-9 rounded-xl bg-rose-500/10 border border-rose-400/20 text-rose-300 hover:bg-rose-500 hover:text-white transition inline-flex items-center justify-center">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalAddUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" data-close-modal></div>

        <div class="relative w-full max-w-lg rounded-[1.7rem] border border-white/10 bg-slate-900/95 backdrop-blur shadow-soft overflow-hidden">
            <div class="p-5 lg:p-6 border-b border-white/10 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-extrabold text-white">Tambah Akun Baru</h2>
                    <p class="text-xs text-slate-500 mt-1">Buat akses baru untuk superadmin atau kasir.</p>
                </div>
                <button type="button" data-close-modal class="w-9 h-9 rounded-xl bg-white/[.04] border border-white/10 text-slate-400 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="#" method="post" class="p-5 lg:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" placeholder="Contoh: Kasir Utama" class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Username
                    </label>
                    <input type="text" placeholder="Contoh: kasir01" class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Password
                    </label>
                    <input type="password" placeholder="Minimal 6 karakter" class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Role Akses
                    </label>
                    <select class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                        <option class="bg-slate-950" value="kasir">Kasir</option>
                        <option class="bg-slate-950" value="superadmin">Superadmin</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" data-close-modal class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-bold text-slate-300 transition">
                        Batal
                    </button>
                    <button type="button" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    (function () {
        const modal = document.getElementById('modalAddUser');
        const openBtn = document.getElementById('btnOpenAddUser');
        if (!modal || !openBtn) return;
        const open = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
        const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
        openBtn.addEventListener('click', open);
        modal.querySelectorAll('[data-close-modal]').forEach(el => el.addEventListener('click', close));
    })();
</script>

<?= $this->endSection() ?>
