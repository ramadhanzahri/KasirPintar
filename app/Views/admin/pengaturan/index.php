<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$errors = session()->getFlashdata('errors') ?? [];

$storeName        = old('store_name', $settings['store_name'] ?? 'ARZANA');
$storeDescription = old('store_description', $settings['store_description'] ?? '');

$storeLogo    = $settings['store_logo'] ?? '';
$storeFavicon = $settings['store_favicon'] ?? '';
?>

<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Pengaturan Toko</h1>
        <p class="text-sm text-slate-400 mt-1">
            Atur identitas toko seperti nama, logo, favicon, dan kontak.
        </p>
    </div>

    <a href="<?= base_url('admin/dashboard') ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 text-sm text-slate-300 hover:text-white transition">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (! empty($errors)): ?>
    <div class="mb-5 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
        <div class="flex items-center gap-2 font-semibold mb-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            Ada data yang perlu diperbaiki
        </div>
        <ul class="list-disc pl-5 space-y-1">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/pengaturan/update') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

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
                               value="<?= esc($storeName) ?>"
                               placeholder="Contoh: ARZANA"
                               class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            Deskripsi / Slogan Singkat
                        </label>
                        <input type="text"
                               name="store_description"
                               value="<?= esc($storeDescription) ?>"
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
                                <?php if (! empty($storeLogo)): ?>
                                    <img src="<?= base_url($storeLogo) ?>" alt="Logo Toko" class="w-full h-full object-contain p-2">
                                <?php else: ?>
                                    <i class="fa-solid fa-store text-slate-500 text-2xl"></i>
                                <?php endif; ?>
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
                                <?php if (! empty($storeFavicon)): ?>
                                    <img src="<?= base_url($storeFavicon) ?>" alt="Favicon" class="w-full h-full object-contain p-2">
                                <?php elseif (! empty($storeLogo)): ?>
                                    <img src="<?= base_url($storeLogo) ?>" alt="Favicon" class="w-full h-full object-contain p-2">
                                <?php else: ?>
                                    <i class="fa-solid fa-globe text-slate-500 text-2xl"></i>
                                <?php endif; ?>
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
                            <?php if (! empty($storeLogo)): ?>
                                <img src="<?= base_url($storeLogo) ?>" alt="Logo" class="w-full h-full object-contain p-2">
                            <?php else: ?>
                                <i class="fa-solid fa-store text-slate-500 text-xl"></i>
                            <?php endif; ?>
                        </div>

                        <div class="min-w-0">
                            <p class="text-white font-bold truncate"><?= esc($storeName) ?></p>
                            <p class="text-xs text-slate-400 truncate"><?= esc($storeDescription ?: 'Deskripsi toko belum diisi') ?></p>
                        </div>
                    </div>


                </div>

                <div class="mt-5 flex flex-col gap-3">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Pengaturan
                    </button>

                    <a href="<?= base_url('admin/dashboard') ?>"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>

<?= $this->endSection() ?>