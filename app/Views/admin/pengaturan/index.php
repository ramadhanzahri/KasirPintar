<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Pengaturan Toko</h1>
        <p class="text-sm text-slate-400 mt-1">
            Atur identitas toko seperti nama, logo, favicon, dan kontak.
        </p>
    </div>

    <a href="#"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 text-sm text-slate-300 hover:text-white transition">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>
</div>

<form action="#" method="post" enctype="multipart/form-data">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 space-y-6">

            <div class="rounded-3xl border border-white/10 bg-white/[.035] backdrop-blur p-5 lg:p-6 shadow-soft">
                <div class="mb-5">
                    <h2 class="text-base font-bold text-white">Informasi Utama</h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Data ini akan ditampilkan pada dashboard, checkout, halaman sukses, dan footer.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            Nama Toko <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="store_name"
                               value="Nama Toko"
                               placeholder="Contoh: Nama TOko"
                               class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            Deskripsi / Slogan Singkat
                        </label>
                        <input type="text"
                               name="store_description"
                               value="Sistem Kasir Pintar Modern"
                               placeholder="Contoh: Menyajikan hidangan terbaik untuk Anda."
                               class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/[.035] backdrop-blur p-5 lg:p-6 shadow-soft">
                <div class="mb-5">
                    <h2 class="text-base font-bold text-white">Logo & Favicon</h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Logo dipakai di sidebar dan halaman sistem. Favicon dipakai untuk icon tab browser.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden">
                                <i class="fa-solid fa-store text-slate-500 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Logo Toko</p>
                                <p class="text-xs text-slate-500 mt-1">PNG, JPG, atau WEBP. Maksimal 2MB.</p>
                            </div>
                        </div>

                        <label class="block">
                            <span class="sr-only">Upload Logo</span>
                            <input type="file"
                                   name="store_logo"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:rounded-xl file:border-0 file:bg-sky-500/15 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-sky-300 hover:file:bg-sky-500/25">
                        </label>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden">
                                <i class="fa-solid fa-globe text-slate-500 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Favicon</p>
                                <p class="text-xs text-slate-500 mt-1">Icon tab Chrome. PNG/JPG/WEBP, maksimal 1MB.</p>
                            </div>
                        </div>

                        <label class="block">
                            <span class="sr-only">Upload Favicon</span>
                            <input type="file"
                                   name="store_favicon"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:rounded-xl file:border-0 file:bg-purple-500/15 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-purple-300 hover:file:bg-purple-500/25">
                        </label>
                    </div>

                </div>
            </div>

        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-white/[.035] backdrop-blur p-5 lg:p-6 shadow-soft sticky top-24">
                <h2 class="text-base font-bold text-white mb-4">Preview</h2>

                <div class="rounded-3xl bg-slate-950/60 border border-white/10 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-store text-slate-500 text-xl"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-white font-bold truncate">Nama Toko</p>
                            <p class="text-xs text-slate-400 truncate">Sistem Kasir Pintar Modern</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-3">
                    <button type="button"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Pengaturan
                    </button>

                    <a href="#"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>

<?= $this->endSection() ?>
