<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
helper('app_setting');

$storeNameForReceipt = app_setting('store_name', 'Warung');

$formatMetode = static function ($metode) {
    return match ($metode) {
        'tunai'    => 'Tunai',
        'qris'     => 'QRIS / E-Wallet',
        'transfer' => 'Transfer Bank',
        default    => ucfirst((string) $metode),
    };
};

$statusBadgeClass = static function ($status) {
    return match ($status) {
        'selesai' => 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20',
        'pending' => 'bg-amber-500/10 text-amber-300 border-amber-400/20',
        default   => 'bg-slate-500/10 text-slate-300 border-slate-400/20',
    };
};

$statusLabel = static function ($status) {
    return match ($status) {
        'selesai' => 'Selesai',
        'pending' => 'Pending',
        default   => ucfirst((string) $status),
    };
};

$formatRupiah = static function ($value): string {
    return 'Rp ' . number_format((int) $value, 0, ',', '.');
};

$receiptData = static function (array $p) use ($formatMetode): array {
    $items = [];
    $subtotal = 0;

    foreach (($p['items'] ?? []) as $item) {
        $harga = (int) ($item['harga'] ?? 0);
        $qty   = (int) ($item['qty'] ?? 0);
        $sub   = (int) ($item['sub_total'] ?? ($harga * qty));

        $subtotal += $sub;

        $items[] = [
            'nama_menu' => $item['nama_menu'] ?? '-',
            'harga'     => $harga,
            'qty'       => $qty,
            'subtotal'  => $sub,
        ];
    }

    $total    = (int) ($p['total_harga'] ?? 0);

    if ($subtotal <= 0 && $total > 0) {
        $subtotal = $total;
    }

    return [
        'id'                => $p['kode_pesanan'] ?? '-',
        'kode_pesanan'      => $p['kode_pesanan'] ?? '-',
        'nama_pemesan'      => $p['nama_pemesan'] ?? '-',
        'metode_pembayaran' => $p['metode_pembayaran'] ?? '-',
        'metode_label'      => $formatMetode($p['metode_pembayaran'] ?? '-'),
        'status'            => $p['status_pembayaran'] ?? 'selesai',
        'catatan'           => $p['catatan'] ?? '',
        'created_at'        => $p['created_at'] ?? date('Y-m-d H:i:s'),
        'updated_at'        => $p['updated_at'] ?? ($p['created_at'] ?? date('Y-m-d H:i:s')),
        'waktu'             => ! empty($p['updated_at']) ? date('d M Y, H:i', strtotime($p['updated_at'])) : date('d M Y, H:i'),
        'kasir_nama'        => $p['kasir_nama'] ?? '-',
        'total_harga'       => $total,
        'items'             => $items,
    ];
};

$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
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
                        Riwayat Kasir
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Riwayat Transaksi
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Daftar pesanan yang sudah selesai diproses, lengkap dengan detail transaksi dan cetak ulang struk.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/[.04] border border-white/10 p-4 min-w-full sm:min-w-[280px] lg:min-w-[320px]">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-400/20 flex items-center justify-center text-emerald-300">
                            <i class="fa-solid fa-receipt"></i>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-white">Total Transaksi</p>
                            <p class="text-2xl font-extrabold text-white mt-1">
                                <?= number_format((int) $total_pesanan, 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-[1.5rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-400/20 flex items-center justify-center text-emerald-300">
                    <i class="fa-solid fa-receipt"></i>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.16em]">Total Pesanan</p>
                    <p class="text-2xl font-extrabold text-white mt-1">
                        <?= number_format((int) $total_pesanan, 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-400/20 flex items-center justify-center text-sky-300">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.16em]">Total Pendapatan</p>
                    <p class="text-2xl font-extrabold text-white mt-1">
                        <?= $formatRupiah($total_pendapatan) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft p-5 lg:p-6">
        <form action="<?= base_url('kasir/riwayat') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
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

                <a href="<?= base_url('kasir/riwayat') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-4 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-white">
                    Daftar Transaksi Selesai
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Klik detail untuk melihat item pesanan. Klik cetak untuk mencetak ulang struk transaksi.
                </p>
            </div>

            <span class="inline-flex items-center gap-2 text-xs bg-white/[.04] border border-white/10 text-slate-400 px-3 py-2 rounded-2xl">
                <i class="fa-solid fa-calendar-day text-sky-300"></i>
                <?= (!empty($start_date) || !empty($end_date)) ? (esc($start_date ?: '...') . ' s/d ' . esc($end_date ?: '...')) : 'Hari Ini' ?>
            </span>
        </div>

        <?php if (empty($riwayat)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center">
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
                        <tr class="bg-white/[.025] border-b border-white/10 text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                            <th class="px-5 py-4 whitespace-nowrap">Waktu Selesai</th>
                            <th class="px-5 py-4 whitespace-nowrap">ID Pesanan</th>
                            <th class="px-5 py-4 whitespace-nowrap">Pelanggan</th>
                            <th class="px-5 py-4 whitespace-nowrap">Dilayani</th>
                            <th class="px-5 py-4 whitespace-nowrap">Metode</th>
                            <th class="px-5 py-4 whitespace-nowrap">Total</th>
                            <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        <?php foreach ($riwayat as $p): ?>
                            <?php
                            $dataStruk = $receiptData($p);
                            $jsonStruk = json_encode($dataStruk, $jsonFlags);
                            ?>

                            <tr class="hover:bg-white/[.025] transition">
                                <td class="px-5 py-4 text-sm text-slate-400 whitespace-nowrap">
                                    <?= ! empty($p['updated_at']) ? date('d M Y, H:i', strtotime($p['updated_at'])) : '-' ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-2 text-xs font-black bg-sky-500/10 text-sky-300 border border-sky-400/20 px-3 py-1.5 rounded-full">
                                        <i class="fa-solid fa-hashtag"></i>
                                        <?= esc($p['kode_pesanan']) ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm font-bold text-white whitespace-nowrap">
                                    <?= esc($p['nama_pemesan']) ?>
                                </td>

                                <td class="px-5 py-4 text-xs text-slate-400 whitespace-nowrap">
                                    <?= esc($p['kasir_nama'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center text-[11px] px-2.5 py-1 rounded-full border bg-white/[.04] text-slate-300 border-white/10">
                                        <i class="fa-solid fa-wallet mr-1.5 text-sky-300"></i>
                                        <?= esc($formatMetode($p['metode_pembayaran'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm font-extrabold text-white whitespace-nowrap">
                                    <?= $formatRupiah($p['total_harga']) ?>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-3 py-2 text-xs font-bold text-slate-300 hover:text-white transition"
                                            onclick="toggleDetail('detail-<?= esc($p['kode_pesanan']) ?>')">
                                            <i class="fa-solid fa-eye"></i>
                                            Detail
                                        </button>

                                        <button type="button"
                                            onclick='cetakStruk(<?= $jsonStruk ?>)'
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/20 px-3 py-2 text-xs font-bold text-sky-300 hover:text-white transition">
                                            <i class="fa-solid fa-print"></i>
                                            Cetak
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr id="detail-<?= esc($p['kode_pesanan']) ?>" class="hidden bg-slate-950/30">
                                <td colspan="7" class="px-5 py-5">
                                    <div class="rounded-[1.4rem] border border-white/10 bg-white/[.035] p-5">
                                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                                            <div class="flex-1">
                                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.16em] mb-3">
                                                    Item Pesanan
                                                </h4>

                                                <ul class="space-y-2.5">
                                                     <?php foreach (($p['items'] ?? []) as $item): ?>
                                                        <li class="flex items-start justify-between gap-4 text-sm">
                                                            <span class="flex items-start gap-2 text-slate-300">
                                                                <i class="fa-solid fa-circle-dot text-slate-600 text-[9px] mt-1.5"></i>

                                                                <span>
                                                                    <span class="font-bold text-white"><?= esc($item['nama_menu']) ?></span>
                                                                    <span class="text-sky-300 font-bold">x<?= esc($item['qty']) ?></span>
                                                                </span>
                                                            </span>

                                                             <span class="text-xs font-bold text-slate-300 whitespace-nowrap">
                                                                 <?= $formatRupiah($item['sub_total'] ?? (($item['harga'] ?? 0) * ($item['qty'] ?? 0))) ?>
                                                             </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>

                                                <?php if (! empty($p['catatan'])): ?>
                                                    <div class="mt-4 text-xs text-amber-300 bg-amber-500/10 border border-amber-400/20 rounded-2xl px-4 py-3">
                                                        <i class="fa-solid fa-note-sticky mr-1"></i>
                                                        Catatan: <?= esc($p['catatan']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="w-full lg:w-[280px] rounded-2xl border border-white/10 bg-slate-950/50 p-4">
                                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.16em] mb-3">
                                                    Ringkasan
                                                </h4>

                                                        <div class="space-y-2 text-sm">
                                                    <div class="border-t border-white/10 pt-3 mt-3 flex justify-between gap-4">
                                                        <span class="font-extrabold text-white">Total</span>
                                                        <span class="font-extrabold text-white"><?= $formatRupiah($p['total_harga']) ?></span>
                                                    </div>
                                                </div>

                                                <button type="button"
                                                    onclick='cetakStruk(<?= $jsonStruk ?>)'
                                                    class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-4 py-3 text-xs font-bold text-white shadow-glow hover:opacity-95 transition">
                                                    <i class="fa-solid fa-print"></i>
                                                    Cetak Struk
                                                </button>
                                            </div>
                                        </div>
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
    const STORE_NAME = <?= json_encode($storeNameForReceipt) ?>;

    function toggleDetail(id) {
        const el = document.getElementById(id);

        if (!el) {
            return;
        }

        el.classList.toggle('hidden');
    }

    function escapeHTML(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function formatRupiah(number) {
        return String(parseInt(number || 0)).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatMetodeLabel(metode) {
        if (metode === 'tunai') return 'Tunai';
        if (metode === 'qris') return 'QRIS / E-Wallet';
        if (metode === 'transfer') return 'Transfer Bank';
        return metode || '-';
    }

    function normalizeReceiptData(p) {
        let items = Array.isArray(p.items) ? p.items : [];
        let subtotal = parseInt(p.subtotal || 0);

        if (subtotal <= 0 && items.length > 0) {
            subtotal = items.reduce((sum, item) => {
                return sum + (parseInt(item.harga || 0) * parseInt(item.qty || 0));
            }, 0);
        }

        const total = parseInt(p.total_harga || 0);

        if (subtotal <= 0 && total > 0) {
            subtotal = total;
        }

        return {
            id: p.id || 0,
            kode_pesanan: p.kode_pesanan || '-',
            nama_pemesan: p.nama_pemesan || '-',
            metode_label: p.metode_label || formatMetodeLabel(p.metode_pembayaran || '-'),
            status: p.status || 'selesai',
            catatan: p.catatan || '',
            waktu: p.waktu || '-',
            kasir_nama: p.kasir_nama || '-',
            total_harga: total,
            items: items
        };
    }

    function cetakStruk(rawData) {
        const data = normalizeReceiptData(rawData);

        const itemsHTML = data.items.length > 0 ?
            data.items.map(item => {
                const nama = escapeHTML(item.nama_menu || '-');
                const qty = parseInt(item.qty || 0);
                const harga = parseInt(item.harga || 0);
                const sub = parseInt(item.subtotal || (harga * qty));
                const note = item.catatan ?
                    `<div class="note">Catatan: ${escapeHTML(item.catatan)}</div>` :
                    '';

                return `
                    <div class="item">
                        <div>
                            <div class="item-name">${nama}</div>
                            <div class="muted">${qty} x Rp ${formatRupiah(harga)}</div>
                            ${note}
                        </div>
                        <div class="item-price">Rp ${formatRupiah(sub)}</div>
                    </div>
                `;
            }).join('') :
            `<div class="empty-item">Detail item tidak tersedia.</div>`;

        const catatanHTML = data.catatan ?
            `<div class="catatan">Catatan: ${escapeHTML(data.catatan)}</div>` :
            '';

        const html = `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Struk ${escapeHTML(data.kode_pesanan)}</title>
                <style>
                    * { box-sizing: border-box; }

                    body {
                        margin: 0;
                        background: #fff;
                        color: #000;
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                    }

                    .receipt {
                        width: 58mm;
                        padding: 10px;
                        margin: 0 auto;
                    }

                    .center { text-align: center; }

                    .store {
                        font-size: 15px;
                        font-weight: 800;
                        text-transform: uppercase;
                        margin-bottom: 3px;
                    }

                    .small {
                        font-size: 10px;
                        line-height: 1.4;
                    }

                    .line {
                        border-top: 1px dashed #000;
                        margin: 8px 0;
                    }

                    .row {
                        display: flex;
                        justify-content: space-between;
                        gap: 8px;
                        margin: 3px 0;
                    }

                    .label { color: #333; }

                    .value {
                        font-weight: 700;
                        text-align: right;
                    }

                    .item {
                        display: flex;
                        justify-content: space-between;
                        gap: 8px;
                        margin-bottom: 7px;
                    }

                    .item-name {
                        font-weight: 700;
                        max-width: 34mm;
                    }

                    .item-price {
                        font-weight: 700;
                        white-space: nowrap;
                    }

                    .muted {
                        color: #444;
                        font-size: 10px;
                        margin-top: 2px;
                    }

                    .note {
                        font-size: 10px;
                        margin-top: 2px;
                        color: #111;
                        font-style: italic;
                    }

                    .catatan {
                        border: 1px dashed #000;
                        padding: 5px;
                        margin-top: 7px;
                        font-size: 10px;
                    }

                    .total-row {
                        font-size: 14px;
                        font-weight: 800;
                    }

                    .empty-item {
                        font-size: 10px;
                        text-align: center;
                        color: #444;
                        padding: 6px 0;
                    }

                    .thanks {
                        margin-top: 10px;
                        text-align: center;
                        font-size: 10px;
                        line-height: 1.4;
                    }

                    @media print {
                        @page {
                            size: 58mm auto;
                            margin: 0;
                        }

                        body { width: 58mm; }
                        .receipt { width: 58mm; }
                    }
                </style>
            </head>
            <body>
                <div class="receipt">
                    <div class="center">
                        <div class="store">${escapeHTML(STORE_NAME)}</div>
                        <div class="small">Struk Transaksi</div>
                    </div>

                    <div class="line"></div>

                    <div class="row">
                        <span class="label">Kode</span>
                        <span class="value">${escapeHTML(data.kode_pesanan)}</span>
                    </div>
                    <div class="row">
                        <span class="label">Nama</span>
                        <span class="value">${escapeHTML(data.nama_pemesan)}</span>
                    </div>
                    <div class="row">
                        <span class="label">Metode</span>
                        <span class="value">${escapeHTML(data.metode_label)}</span>
                    </div>
                    <div class="row">
                        <span class="label">Waktu</span>
                        <span class="value">${escapeHTML(data.waktu)}</span>
                    </div>
                    <div class="row">
                        <span class="label">Dilayani</span>
                        <span class="value">${escapeHTML(data.kasir_nama || '-')}</span>
                    </div>

                    <div class="line"></div>

                    ${itemsHTML}

                    ${catatanHTML}

                    <div class="line"></div>

                     <div class="row total-row">
                        <span>Total</span>
                        <span>Rp ${formatRupiah(data.total_harga)}</span>
                    </div>

                    <div class="line"></div>

                    <div class="thanks">
                        Terima kasih.<br>
                        Pesanan telah selesai.<br>
                        Simpan struk ini sebagai bukti transaksi.
                    </div>
                </div>

                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 500);
                        }, 250);
                    };
                <\/script>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank', 'width=380,height=650');

        if (!printWindow) {
            alert('Popup print diblokir browser. Izinkan popup untuk mencetak struk.');
            return;
        }

        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();
    }
</script>

<?= $this->endSection() ?>