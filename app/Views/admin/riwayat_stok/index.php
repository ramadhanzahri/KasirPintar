<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-orange-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-500/10 border border-orange-400/20 text-orange-300 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-box"></i>
                        Riwayat Stok
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Riwayat Stok
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Kelola dan pantau riwayat stok menu. Semua data tersimpan permanen dan dapat dicari menggunakan filter tanggal.
                    </p>
                </div>

                <a href="<?= base_url('admin/dashboard') ?>"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 text-sm font-semibold text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Dashboard
                </a>
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

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft p-5 lg:p-6">
        <form action="<?= base_url('admin/riwayat-stok') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                    Dari Tanggal
                </label>
                <input type="date"
                       name="start_date"
                       value="<?= esc($start_date ?? '') ?>"
                       class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                    Sampai Tanggal
                </label>
                <input type="date"
                       name="end_date"
                       value="<?= esc($end_date ?? '') ?>"
                       class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                    <i class="fa-solid fa-filter"></i>
                    Terapkan
                </button>

                <a href="<?= base_url('admin/riwayat-stok') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-4 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-white/10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-white">Data Riwayat Stok</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar riwayat stok yang tercatat otomatis dari inputan stok menu.
                </p>
            </div>
        </div>

        <?php if (empty($riwayat)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center px-5">
                <div class="w-16 h-16 rounded-3xl bg-white/[.04] border border-white/10 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-box text-slate-500 text-2xl"></i>
                </div>
                <p class="text-sm font-bold text-white">Belum ada riwayat stok</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/[.025] text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                            <th class="px-5 py-4 whitespace-nowrap">Nama Menu</th>
                            <th class="px-5 py-4 text-center whitespace-nowrap">Stok</th>
                            <th class="px-5 py-4 whitespace-nowrap">Tanggal</th>
                            <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        <?php foreach ($riwayat as $r): ?>
                            <tr class="hover:bg-white/[.025] transition">
                                <td class="px-5 py-4 text-sm font-semibold text-white whitespace-nowrap">
                                    <?= esc($r['nama_menu'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-bold text-sky-300 whitespace-nowrap">
                                    <?= esc($r['stok'] ?? 0) ?>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">
                                    <?= !empty($r['tanggal']) ? date('d M Y', strtotime($r['tanggal'])) : '-' ?>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <?php if (($r['tanggal'] ?? '') === date('Y-m-d')): ?>
                                        <button type="button"
                                                onclick="confirmDelete('<?= base_url('admin/riwayat-stok/delete/' . $r['id']) ?>', '<?= esc($r['nama_menu'] ?? '-', 'js') ?>', '<?= esc($r['stok'] ?? 0, 'js') ?>')"
                                                class="inline-flex items-center gap-2 text-xs font-bold text-rose-300 hover:text-white bg-rose-500/10 hover:bg-rose-500/20 border border-rose-400/20 px-3 py-2 rounded-xl transition">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-white/[.02] border border-white/5 px-3 py-1.5 rounded-xl cursor-not-allowed" title="Hanya riwayat hari ini yang dapat dihapus">
                                            <i class="fa-solid fa-lock text-[10px]"></i> Terkunci
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Stok -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <div class="relative w-full max-w-sm rounded-[2rem] border border-white/10 bg-slate-900/95 p-6 backdrop-blur shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl animate-pulse"></i>
        </div>

        <h3 class="text-lg font-extrabold text-white mb-2">Hapus Riwayat Stok?</h3>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Stok menu <span id="deleteMenuName" class="text-white font-bold"></span> sebanyak <span id="deleteMenuQty" class="text-amber-400 font-bold"></span> akan dibatalkan dan dikurangi dari total stok.
        </p>

        <form id="deleteForm" action="" method="post" class="flex items-center gap-3">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 py-3 text-xs font-bold text-slate-300 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 rounded-2xl bg-rose-600 hover:bg-rose-700 py-3 text-xs font-bold text-white shadow-glow transition">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function confirmDelete(actionUrl, menuName, qty) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteMenuName').textContent = menuName;
        document.getElementById('deleteMenuQty').textContent = qty;

        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>

<?= $this->endSection() ?>
