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
                                <?= number_format(count($users ?? []), 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

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
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-3xl bg-white/[.04] border border-white/10 flex items-center justify-center">
                                                <i class="fa-solid fa-users text-slate-500 text-2xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white">Belum ada data akun</p>
                                                <p class="text-xs text-slate-500 mt-1">Tambahkan akun kasir baru melalui tombol Tambah Akun di atas.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
                                    <tr class="hover:bg-white/[.025] transition">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-purple-600 p-[2px]">
                                                    <div class="w-full h-full rounded-full bg-slate-950 flex items-center justify-center text-slate-300">
                                                        <i class="fa-solid fa-user text-xs"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <p class="text-sm font-bold text-white">
                                                        <?= esc($u['nama_lengkap'] ?? '-') ?>
                                                    </p>
                                                    <p class="text-xs text-slate-500">
                                                        ID: <?= esc($u['id'] ?? '-') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">
                                            <?= esc($u['username'] ?? '-') ?>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <?php if (($u['role'] ?? '') === 'superadmin'): ?>
                                                <span class="text-[10px] font-bold text-purple-300 bg-purple-500/10 border border-purple-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                                    Superadmin
                                                </span>
                                            <?php else: ?>
                                                <span class="text-[10px] font-bold text-sky-300 bg-sky-500/10 border border-sky-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                                    Kasir
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <?php if (($u['is_active'] ?? 1) == 1): ?>
                                                <span class="text-[10px] font-bold text-emerald-300 bg-emerald-500/10 border border-emerald-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 w-fit">
                                                    <i class="fa-solid fa-circle"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="text-[10px] font-bold text-red-300 bg-red-500/10 border border-red-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 w-fit">
                                                    <i class="fa-solid fa-circle"></i> Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if (($u['role'] ?? '') === 'kasir'): ?>
                                            <button type="button"
                                                    onclick="editUser('<?= base_url('admin/users/edit/' . $u['id']) ?>', '<?= base_url('admin/users/update/' . $u['id']) ?>')"
                                                    class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-400/20 text-sky-300 hover:bg-sky-500 hover:text-white transition inline-flex items-center justify-center"
                                                    title="Edit akun">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="w-9 h-9 rounded-xl bg-white/[.02] border border-white/5 text-slate-600 inline-flex items-center justify-center cursor-not-allowed" title="Profil superadmin tidak dapat diedit dari sini">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </span>
                                        <?php endif; ?>

                                        <button type="button"
                                                onclick="confirmToggleUser('<?= base_url('admin/users/toggle/' . $u['id']) ?>', <?= esc($u['id']) ?>, <?= esc($u['is_active']) ?>)"
                                                class="w-9 h-9 rounded-xl transition inline-flex items-center justify-center <?= (($u['is_active'] ?? 1) == 1) ? 'bg-amber-500/10 border border-amber-400/20 text-amber-300 hover:bg-amber-500 hover:text-white' : 'bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 hover:bg-emerald-500 hover:text-white' ?> disabled:opacity-40 disabled:cursor-not-allowed"
                                                title="<?= (($u['is_active'] ?? 1) == 1) ? 'Nonaktifkan akun' : 'Aktifkan akun' ?>"
                                                <?= (session()->get('id') == ($u['id'] ?? null) || ($u['id'] == 1)) ? 'disabled' : '' ?>>
                                            <?php if (($u['is_active'] ?? 1) == 1): ?>
                                                <i class="fa-solid fa-toggle-on text-lg"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-toggle-off text-lg"></i>
                                            <?php endif; ?>
                                        </button>

                                        <button type="button"
                                                onclick="confirmDeleteUser('<?= base_url('admin/users/delete/' . $u['id']) ?>', '<?= esc($u['nama_lengkap'] ?? '-', 'js') ?>')"
                                                class="w-9 h-9 rounded-xl bg-rose-500/10 border border-rose-400/20 text-rose-300 hover:bg-rose-500 hover:text-white transition inline-flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed"
                                                <?= (session()->get('id') == ($u['id'] ?? null) || ($u['id'] == 1)) ? 'disabled' : '' ?>>
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
    </div>

    <div id="modalEditUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" data-close-modal-edit></div>

        <div class="relative w-full max-w-lg rounded-[1.7rem] border border-white/10 bg-slate-900/95 backdrop-blur shadow-soft overflow-hidden">
            <div class="p-5 lg:p-6 border-b border-white/10 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-extrabold text-white">Edit Akun</h2>
                    <p class="text-xs text-slate-500 mt-1">Ubah informasi akun.</p>
                </div>
                <button type="button" data-close-modal-edit class="w-9 h-9 rounded-xl bg-white/[.04] border border-white/10 text-slate-400 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formEditUser" action="" method="post" class="p-5 lg:p-6 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           id="edit_nama_lengkap"
                           name="nama_lengkap"
                           required
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Username
                    </label>
                    <input type="text"
                           id="edit_username"
                           name="username"
                           required
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Password (Kosongkan jika tidak ingin diubah)
                    </label>
                    <input type="password"
                           name="password"
                           minlength="6"
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Role Akses
                    </label>
                    <select id="edit_role"
                            name="role"
                            required
                            class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                        <option class="bg-slate-950 text-white" value="kasir">Kasir</option>
                        <option class="bg-slate-950 text-white" value="superadmin">Superadmin</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" data-close-modal-edit class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-bold text-slate-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="modalEditUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" data-close-modal-edit></div>

        <div class="relative w-full max-w-lg rounded-[1.7rem] border border-white/10 bg-slate-900/95 backdrop-blur shadow-soft overflow-hidden">
            <div class="p-5 lg:p-6 border-b border-white/10 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-extrabold text-white">Edit Akun</h2>
                    <p class="text-xs text-slate-500 mt-1">Ubah informasi akun.</p>
                </div>
                <button type="button" data-close-modal-edit class="w-9 h-9 rounded-xl bg-white/[.04] border border-white/10 text-slate-400 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formEditUser" action="" method="post" class="p-5 lg:p-6 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           id="edit_nama_lengkap"
                           name="nama_lengkap"
                           required
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Username
                    </label>
                    <input type="text"
                           id="edit_username"
                           name="username"
                           required
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Password (Kosongkan jika tidak ingin diubah)
                    </label>
                    <input type="password"
                           name="password"
                           minlength="6"
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Role Akses
                    </label>
                    <select id="edit_role"
                            name="role"
                            required
                            class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                        <option class="bg-slate-950 text-white" value="kasir">Kasir</option>
                        <option class="bg-slate-950 text-white" value="superadmin">Superadmin</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" data-close-modal-edit class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-bold text-slate-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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

            <form action="<?= base_url('admin/users/store') ?>" method="post" class="p-5 lg:p-6 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="nama_lengkap"
                           required
                           placeholder="Contoh: Kasir Utama"
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Username
                    </label>
                    <input type="text"
                           name="username"
                           required
                           placeholder="Contoh: kasir01"
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           required
                           minlength="6"
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Role Akses
                    </label>
                    <select name="role"
                            required
                            class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                        <option class="bg-slate-950 text-white" value="kasir">Kasir</option>
                        <option class="bg-slate-950 text-white" value="superadmin">Superadmin</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" data-close-modal class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-bold text-slate-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Toggle Status User -->
<div id="toggleUserModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeToggleUserModal()"></div>

    <div class="relative w-full max-w-sm rounded-[2rem] border border-white/10 bg-slate-900/95 p-6 backdrop-blur shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-toggle-on text-2xl" id="toggleUserIcon"></i>
        </div>

        <h3 class="text-lg font-extrabold text-white mb-2" id="toggleUserTitle">Nonaktifkan Akun?</h3>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed" id="toggleUserMessage">
            Akun akan dinonaktifkan dan pengguna tidak dapat login.
        </p>

        <form id="toggleUserForm" action="" method="post" class="flex items-center gap-3">
            <?= csrf_field() ?>
            <button type="button" onclick="closeToggleUserModal()" class="flex-1 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 py-3 text-xs font-bold text-slate-300 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 rounded-2xl bg-amber-600 hover:bg-amber-700 py-3 text-xs font-bold text-white shadow-glow transition" id="toggleUserBtn">
                Ya, Nonaktifkan
            </button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus User -->
<div id="deleteUserModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeDeleteUserModal()"></div>

    <div class="relative w-full max-w-sm rounded-[2rem] border border-white/10 bg-slate-900/95 p-6 backdrop-blur shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl animate-pulse"></i>
        </div>

        <h3 class="text-lg font-extrabold text-white mb-2">Hapus Akun?</h3>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Akun <span id="deleteUserName" class="text-white font-bold"></span> akan dihapus secara permanen dari sistem.
        </p>

        <form id="deleteUserForm" action="" method="post" class="flex items-center gap-3">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeleteUserModal()" class="flex-1 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 py-3 text-xs font-bold text-slate-300 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 rounded-2xl bg-rose-600 hover:bg-rose-700 py-3 text-xs font-bold text-white shadow-glow transition">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function editUser(getDataUrl, updateUrl) {
        const modal = document.getElementById('modalEditUser');
        const form = document.getElementById('formEditUser');
        
        fetch(getDataUrl)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
                document.getElementById('edit_username').value = data.username;
                document.getElementById('edit_role').value = data.role;
                form.action = updateUrl;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            });
    }

    (function () {
        const modalEdit = document.getElementById('modalEditUser');
        if (!modalEdit) return;

        const closeEdit = () => {
            modalEdit.classList.add('hidden');
            modalEdit.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        };

        modalEdit.querySelectorAll('[data-close-modal-edit]').forEach(el => el.addEventListener('click', closeEdit));
    })();

    (function () {
        const modal = document.getElementById('modalAddUser');
        const openBtn = document.getElementById('btnOpenAddUser');
        if (!modal || !openBtn) return;

        const open = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            const first = modal.querySelector('input[name="nama_lengkap"]');
            if (first) setTimeout(() => first.focus(), 50);
        };

        const close = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        };

        openBtn.addEventListener('click', open);
        modal.querySelectorAll('[data-close-modal]').forEach(el => el.addEventListener('click', close));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
    })();

    function confirmToggleUser(actionUrl, userId, currentStatus) {
        document.getElementById('toggleUserForm').action = actionUrl;
        
        const icon = document.getElementById('toggleUserIcon');
        const title = document.getElementById('toggleUserTitle');
        const message = document.getElementById('toggleUserMessage');
        const btn = document.getElementById('toggleUserBtn');
        const modal = document.getElementById('toggleUserModal');
        
        if (currentStatus == 1) {
            icon.className = 'fa-solid fa-toggle-off text-2xl';
            icon.parentElement.classList.remove('bg-emerald-500/10', 'border-emerald-500/20', 'text-emerald-400');
            icon.parentElement.classList.add('bg-amber-500/10', 'border-amber-500/20', 'text-amber-400');
            title.textContent = 'Nonaktifkan Akun?';
            message.textContent = 'Akun akan dinonaktifkan dan pengguna tidak dapat login ke sistem.';
            btn.textContent = 'Ya, Nonaktifkan';
            btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
            btn.classList.add('bg-amber-600', 'hover:bg-amber-700');
        } else {
            icon.className = 'fa-solid fa-toggle-on text-2xl';
            icon.parentElement.classList.remove('bg-amber-500/10', 'border-amber-500/20', 'text-amber-400');
            icon.parentElement.classList.add('bg-emerald-500/10', 'border-emerald-500/20', 'text-emerald-400');
            title.textContent = 'Aktifkan Akun?';
            message.textContent = 'Akun akan diaktifkan kembali dan pengguna dapat login ke sistem.';
            btn.textContent = 'Ya, Aktifkan';
            btn.classList.remove('bg-amber-600', 'hover:bg-amber-700');
            btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeToggleUserModal() {
        const modal = document.getElementById('toggleUserModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDeleteUser(actionUrl, userName) {
        document.getElementById('deleteUserForm').action = actionUrl;
        document.getElementById('deleteUserName').textContent = userName;

        const modal = document.getElementById('deleteUserModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeToggleUserModal();
            closeDeleteUserModal();
        }
    });
</script>

<?= $this->endSection() ?>