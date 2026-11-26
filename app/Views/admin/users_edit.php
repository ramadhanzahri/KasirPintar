<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<div class="max-w-2xl mx-auto space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft p-5 lg:p-7">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-white">Edit Akun</h1>
                <p class="text-xs text-slate-400 mt-1">Ubah informasi akun <?= esc($user['nama_lengkap']) ?></p>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="px-4 py-2 rounded-2xl bg-white/5 border border-white/10 text-xs font-semibold text-slate-300 hover:text-white transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= esc($user['nama_lengkap']) ?>" required class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">Username</label>
                <input type="text" name="username" value="<?= esc($user['username']) ?>" required class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">Password (Kosongkan jika tidak diubah)</label>
                <div class="relative">
                    <input type="password" id="password" name="password" minlength="6" placeholder="Password baru" class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 pr-12 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">Role Akses</label>
                <div class="flex items-center gap-2 w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3">
                    <span class="text-[10px] font-bold text-sky-300 bg-sky-500/10 border border-sky-400/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Kasir
                    </span>
                    <span class="text-xs text-slate-500">Role tidak dapat diubah dari halaman ini.</span>
                </div>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
