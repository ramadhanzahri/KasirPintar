<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$statusClass = static function (?string $status): string {
    return match ($status) {
        'selesai' => 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20',
        'pending' => 'bg-amber-500/10 text-amber-300 border-amber-400/20',
        default   => 'bg-slate-500/10 text-slate-300 border-slate-400/20',
    };
};

$statusLabel = static function (?string $status): string {
    return match ($status) {
        'selesai' => 'Selesai',
        'pending' => 'Menunggu',
        default   => ucfirst((string) $status),
    };
};

$paymentLabel = static function (?string $metode): string {
    return match ($metode) {
        'tunai'    => 'Tunai',
        'qris'     => 'QRIS / E-Wallet',
        'transfer' => 'Transfer Bank',
        default    => ! empty($metode) ? ucfirst((string) $metode) : '-',
    };
};
?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Riwayat Transaksi
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Riwayat Transaksi
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Seluruh daftar pesanan yang telah selesai diproses dan tercatat di sistem.
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

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="relative overflow-hidden rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-emerald-500/20 blur-2xl"></div>

            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-400/20 flex items-center justify-center text-emerald-300">
                    <i class="fa-solid fa-receipt text-lg"></i>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-[0.16em]">
                        Total Pesanan
                    </p>
                    <p class="text-3xl font-extrabold text-white mt-1">
                        <?= number_format($total_pesanan ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-sky-500/20 blur-2xl"></div>

            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-400/20 flex items-center justify-center text-sky-300">
                    <i class="fa-solid fa-sack-dollar text-lg"></i>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-[0.16em]">
                        Total Pendapatan
                    </p>
                    <p class="text-3xl font-extrabold text-white mt-1">
                        Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-purple-500/20 blur-2xl"></div>

            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-400/20 flex items-center justify-center text-purple-300">
                    <i class="fa-solid fa-calendar-day text-lg"></i>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-[0.16em]">
                        Pendapatan Hari Ini
                    </p>
                    <p class="text-3xl font-extrabold text-white mt-1">
                        Rp <?= number_format($pendapatan_hari_ini ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft p-5 lg:p-6">
        <form action="<?= base_url('admin/riwayat') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
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
                    Jam Mulai
                </label>
                <input type="time"
                       name="start_time"
                       value="<?= esc($start_time ?? '') ?>"
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

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                    Jam Akhir
                </label>
                <input type="time"
                       name="end_time"
                       value="<?= esc($end_time ?? '') ?>"
                       class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                    <i class="fa-solid fa-filter"></i>
                    Terapkan
                </button>

                <a href="<?= base_url('admin/riwayat') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-4 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-white/10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-white">Data Transaksi</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Klik tombol detail untuk melihat item pesanan.
                </p>
            </div>
        </div>

        <?php if (empty($riwayat)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center px-5">
                <div class="w-16 h-16 rounded-3xl bg-white/[.04] border border-white/10 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-clock-rotate-left text-slate-500 text-2xl"></i>
                </div>
                <p class="text-sm font-bold text-white">Belum ada riwayat</p>
                <p class="text-xs text-slate-500 mt-1">Transaksi yang sesuai pencarian tidak ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/[.025] text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                            <th class="px-5 py-4 whitespace-nowrap">Waktu Selesai</th>
                            <th class="px-5 py-4 whitespace-nowrap">ID Pesanan</th>
                            <th class="px-5 py-4 whitespace-nowrap">Pelanggan</th>
                            <th class="px-5 py-4 whitespace-nowrap">Dilayani</th>
                            <th class="px-5 py-4 whitespace-nowrap">Metode</th>
                            <th class="px-5 py-4 whitespace-nowrap">Total</th>
                            <th class="px-5 py-4 text-center whitespace-nowrap">Detail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        <?php foreach ($riwayat as $p): ?>
                            <?php
                                $metode = $p['metode_pembayaran'] ?? '';
                                $status = $p['status_pembayaran'] ?? 'selesai';
                            ?>

                            <tr class="hover:bg-white/[.025] transition">
                                <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">
                                    <?= ! empty($p['updated_at']) ? date('d M Y, H:i', strtotime($p['updated_at'])) : '-' ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-xs font-bold bg-white/[.04] text-slate-300 border border-white/10 px-2.5 py-1 rounded-full">
                                        #<?= esc($p['kode_pesanan'] ?? '-') ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-white whitespace-nowrap">
                                    <?= esc($p['nama_pemesan'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 text-xs text-slate-400 whitespace-nowrap">
                                    <?= esc($p['kasir_nama'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">
                                    <?= esc($paymentLabel($metode)) ?>
                                </td>

                                <td class="px-5 py-4 text-sm font-extrabold text-white whitespace-nowrap">
                                    Rp <?= number_format($p['total_harga'] ?? 0, 0, ',', '.') ?>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <button type="button"
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-sky-500/10 hover:bg-sky-500/15 border border-sky-400/20 text-xs font-bold text-sky-300 transition"
                                            onclick="toggleDetail('detail-<?= esc($p['kode_pesanan']) ?>')">
                                        Lihat
                                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr id="detail-<?= esc($p['kode_pesanan']) ?>" class="hidden bg-slate-950/30">
                                <td colspan="7" class="px-5 py-5">
                                    <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5">
                                        <div class="flex items-center justify-between gap-3 mb-4">
                                            <h4 class="text-xs font-bold text-white uppercase tracking-[0.16em]">
                                                Item Pesanan
                                            </h4>

                                            <span class="text-[10px] font-bold px-2 py-1 rounded-full border uppercase tracking-wider <?= $statusClass($status) ?>">
                                                <?= esc($statusLabel($status)) ?>
                                            </span>
                                        </div>

                                        <ul class="space-y-2">
                                            <?php foreach (($p['items'] ?? []) as $item): ?>
                                                <li class="flex items-center justify-between gap-4 text-sm">
                                                    <span class="flex items-center gap-2 text-slate-300">
                                                        <i class="fa-solid fa-circle-dot text-[8px] text-sky-400"></i>
                                                        <?= esc($item['nama_menu'] ?? '-') ?>
                                                        <span class="text-slate-500 text-xs">x<?= esc($item['qty'] ?? 0) ?></span>
                                                    </span>

                                                     <span class="font-bold text-white">
                                                        Rp <?= number_format($item['sub_total'] ?? (($item['harga'] ?? 0) * ($item['qty'] ?? 0)), 0, ',', '.') ?>
                                                     </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <?php if (! empty($p['catatan'])): ?>
                                            <div class="mt-4 pt-4 border-t border-white/10">
                                                <div class="rounded-2xl bg-amber-500/10 border border-amber-400/20 px-4 py-3 text-xs text-amber-300">
                                                    <i class="fa-solid fa-note-sticky mr-1"></i>
                                                    Catatan: <?= esc($p['catatan']) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
    function toggleDetail(id) {
        const el = document.getElementById(id);

        if (!el) {
            return;
        }

        el.classList.toggle('hidden');
    }
</script>

<?= $this->endSection() ?>