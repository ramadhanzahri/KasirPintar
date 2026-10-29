<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8">

    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Pengaturan Akun</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola profil dan keamanan akun Kasir Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Profil Section -->
        <div class="glass-card rounded-3xl p-6 lg:p-8 relative overflow-hidden h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-neon-blue/10 flex items-center justify-center text-neon-blue">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h2 class="text-lg font-bold text-white">Profil Kasir</h2>
            </div>

            <form action="#" method="post" class="space-y-6">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="Kasir Utama" required
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-dark-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-neon-blue focus:ring-1 focus:ring-neon-blue transition">
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-at"></i>
                        </span>
                        <input type="text" id="username" name="username" value="kasir" required
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-dark-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-neon-blue focus:ring-1 focus:ring-neon-blue transition">
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-white font-semibold hover:bg-white/10 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-user-check"></i>
                        Update Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Keamanan Section -->
        <div class="glass-card rounded-3xl p-6 lg:p-8 relative overflow-hidden h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-neon-purple/10 flex items-center justify-center text-neon-purple">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="text-lg font-bold text-white">Ganti Password</h2>
            </div>

            <form action="#" method="post" class="space-y-5">
                <div>
                    <label for="old_password" class="block text-sm font-medium text-slate-300 mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="old_password" name="old_password" required
                            class="password-input w-full pl-11 pr-12 py-3 rounded-2xl bg-dark-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-neon-blue focus:ring-1 focus:ring-neon-blue transition"
                            placeholder="*******">
                        <button type="button" onclick="togglePassword('old_password')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-white transition">
                            <i class="fa-solid fa-eye" id="eye-old_password"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-slate-300 mb-2">Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" id="new_password" name="new_password" required minlength="6"
                            class="password-input w-full pl-11 pr-12 py-3 rounded-2xl bg-dark-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-neon-blue focus:ring-1 focus:ring-neon-blue transition"
                            placeholder="Minimal 6 karakter">
                        <button type="button" onclick="togglePassword('new_password')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-white transition">
                            <i class="fa-solid fa-eye" id="eye-new_password"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-check-double"></i>
                        </span>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                            class="password-input w-full pl-11 pr-12 py-3 rounded-2xl bg-dark-900 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-neon-blue focus:ring-1 focus:ring-neon-blue transition"
                            placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePassword('confirm_password')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-white transition">
                            <i class="fa-solid fa-eye" id="eye-confirm_password"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-neon-blue to-neon-purple text-white font-semibold hover:shadow-glow transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('eye-' + inputId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

<?= $this->endSection() ?>
