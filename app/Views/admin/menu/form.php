<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($menu); ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex items-start gap-4">
                <a href="<?= base_url('admin/menu') ?>"
                   class="w-11 h-11 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 text-slate-300 hover:text-white flex items-center justify-center transition flex-shrink-0">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-400/20 text-amber-300 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-bowl-food"></i>
                        Menu Restoran
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        <?= $isEdit ? 'Edit Menu' : 'Tambah Menu' ?>
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        <?= $isEdit ? 'Perbarui data menu yang sudah tersedia.' : 'Tambahkan item menu baru untuk pelanggan.' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <p class="font-bold mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                Terdapat kesalahan:
            </p>

            <ul class="list-disc list-inside space-y-1 text-xs">
                <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="max-w-5xl">
        <form action="<?= $isEdit ? base_url('admin/menu/update/' . $menu['id']) : base_url('admin/menu/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <div class="xl:col-span-2 rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
                    <div class="p-5 lg:p-6 border-b border-white/10">
                        <h2 class="text-lg font-extrabold text-white">Informasi Menu</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Isi nama, harga, stok, dan status menu.
                        </p>
                    </div>

                    <div class="p-5 lg:p-6 space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                                Nama Menu <span class="text-rose-400">*</span>
                            </label>
                            <input type="text"
                                   name="nama_menu"
                                   value="<?= old('nama_menu', $menu['nama_menu'] ?? '') ?>"
                                   placeholder="Contoh: Es Teh Manis, Nasi Goreng"
                                   class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                                Harga <span class="text-rose-400">*</span>
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-500 font-semibold">Rp</span>
                                <input type="number"
                                       name="harga"
                                       value="<?= old('harga', $menu['harga'] ?? '') ?>"
                                       min="0"
                                       placeholder="15000"
                                       class="w-full rounded-2xl bg-slate-950/60 border border-white/10 pl-11 pr-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                                    Status <span class="text-rose-400">*</span>
                                </label>

                                <select name="is_active"
                                        class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                                    <option class="bg-slate-950 text-white" value="1" <?= old('is_active', $menu['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                                        Aktif
                                    </option>
                                    <option class="bg-slate-950 text-white" value="0" <?= old('is_active', $menu['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden xl:sticky xl:top-24">
                        <div class="p-5 border-b border-white/10">
                            <h2 class="text-lg font-extrabold text-white">Gambar Menu</h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Upload gambar agar menu terlihat menarik.
                            </p>
                        </div>

                        <div class="p-5">
                            <div class="aspect-square rounded-3xl bg-slate-950/60 border border-white/10 flex items-center justify-center overflow-hidden mb-4">
                                <?php if ($isEdit && ! empty($menu['gambar'])): ?>
                                    <img src="<?= base_url('uploads/menu/' . $menu['gambar']) ?>"
                                         alt="<?= esc($menu['nama_menu'] ?? 'Menu') ?>"
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="text-center">
                                        <div class="w-16 h-16 rounded-3xl bg-white/[.04] border border-white/10 flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-image text-slate-500 text-2xl"></i>
                                        </div>
                                        <p class="text-xs text-slate-500">Belum ada gambar</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <input type="file"
                                   name="gambar"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:rounded-xl file:border-0 file:bg-sky-500/15 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-sky-300 hover:file:bg-sky-500/25">

                            <p class="text-xs text-slate-500 mt-3">
                                Format: JPG, PNG, WebP. Maksimal 2MB.
                            </p>

                            <div class="flex flex-col gap-3 mt-6 pt-5 border-t border-white/10">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Menu' ?>
                                </button>

                                <a href="<?= base_url('admin/menu') ?>"
                                   class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>