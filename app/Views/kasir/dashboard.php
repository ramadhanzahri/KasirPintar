<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
helper('app_setting');

$storeNameForReceipt = app_setting('store_name', 'Nama Toko');

$formatMetode = static function ($metode) {
    return match ($metode) {
        'tunai' => 'Tunai / COD',
        'qris' => 'QRIS / E-Wallet',
        'transfer' => 'Transfer Bank / VA',
        default => ucfirst((string) $metode),
    };
};

$statusBadgeClass = static function ($status) {
    return match ($status) {
        'selesai' => 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20',
        'pending' => 'bg-amber-500/10 text-amber-300 border-amber-400/20',
        default => 'bg-slate-500/10 text-slate-300 border-slate-400/20',
    };
};

$statusLabel = static function ($status) {
    return match ($status) {
        'selesai' => 'Pembayaran Selesai',
        'pending' => 'Menunggu Bayar',
        default => ucfirst((string) $status),
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
        $qty = (int) ($item['qty'] ?? 0);
        $sub = $harga * $qty;

        $subtotal += $sub;

        $items[] = [
            'nama_menu' => $item['nama_menu'] ?? '-',
            'harga' => $harga,
            'qty' => $qty,
            'subtotal' => $sub,
        ];
    }

    $total = (int) ($p['total_harga'] ?? 0);

    if ($subtotal <= 0 && $total > 0) {
        $subtotal = $total;
    }

    return [
        'id' => $p['kode_pesanan'] ?? '-',
        'kode_pesanan' => $p['kode_pesanan'] ?? '-',
        'nomor_antrian' => $p['nomor_antrian'] ?? '-',
        'nama_pemesan' => $p['nama_pemesan'] ?? '-',
        'metode_pembayaran' => $p['metode_pembayaran'] ?? '-',
        'metode_label' => $formatMetode($p['metode_pembayaran'] ?? '-'),
        'status' => $p['status_pembayaran'] ?? 'pending',
        'catatan' => $p['catatan'] ?? '',
        'created_at' => $p['created_at'] ?? date('Y-m-d H:i:s'),
        'waktu' => ! empty($p['created_at']) ? date('H:i', strtotime($p['created_at'])) : date('H:i'),
        'total_harga' => $total,
        'items' => $items,
    ];
};

$riwayatHariIni = $riwayat_hari_ini ?? [];
$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-xs font-semibold mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Kasir Live Monitor
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Antrean Pesanan
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Konfirmasi pembayaran tunai, cetak struk, dan tandai pesanan saat sudah siap diantarkan.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] border border-white/10 px-4 py-3 text-sm text-slate-300">
                        <i class="fa-regular fa-clock text-sky-300"></i>
                        <span id="waktu-sekarang" class="font-bold text-white"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php if ($flashSuccess = session()->getFlashdata('success')): ?>
        <script>
            const POS_FLASH_SUCCESS = <?= json_encode((string) $flashSuccess, $jsonFlags) ?>;
        </script>
    <?php endif; ?>

    <?php if ($flashError = session()->getFlashdata('error')): ?>
        <script>
            const POS_FLASH_ERROR = <?= json_encode((string) $flashError, $jsonFlags) ?>;
        </script>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="rounded-[1.5rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-400/20 flex items-center justify-center text-amber-300">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.16em]">Menunggu</p>
                    <p class="text-2xl font-extrabold text-white mt-1" id="count-pending"><?= (int) $total_pending ?></p>
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-white/10 bg-white/[.035] backdrop-blur p-5 shadow-soft">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-400/20 flex items-center justify-center text-sky-300">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.16em]">Pendapatan Hari Ini</p>

                    <p class="text-2xl font-extrabold text-white mt-1" id="pendapatan-hari-ini" data-amount="<?= (int) $pendapatan ?>">
                        <?= $formatRupiah($pendapatan) ?>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">

        <div class="p-5 lg:p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-white">Pesanan Menunggu</h2>

                <p class="text-xs text-slate-500 mt-1">
                    Klik <b>Telah Bayar</b> setelah pembayaran diterima. Pesanan akan otomatis ditandai selesai/siap dan berpindah dari antrean.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 text-xs text-slate-400 bg-white/[.04] border border-white/10 px-3 py-2 rounded-2xl">
                <i class="fa-solid fa-rotate text-sky-300"></i>
                Auto refresh setiap 3 detik
            </div>
        </div>

        <div class="p-4 lg:p-5">

            <div id="order-container" class="space-y-4 <?= empty($pesanan_pending) ? 'hidden' : '' ?>">

                <?php foreach (($pesanan_pending ?? []) as $p): ?>
                    <?php
                    $orderJson = json_encode($receiptData($p), $jsonFlags);

                    $metodePembayaran = strtolower((string) ($p['metode_pembayaran'] ?? ''));
                    $statusBayar = $p['status_pembayaran'] ?? 'pending';

                    $isLunas = $statusBayar === 'selesai';
                    ?>

                    <div class="rounded-[1.5rem] border border-amber-400/20 bg-amber-500/[.04] overflow-hidden shadow-soft" data-order-card="<?= esc($p['kode_pesanan']) ?>">

                        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 px-5 py-4 border-b border-white/10 bg-white/[.025]">

                            <div class="flex items-start gap-4">

                                <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-sky-500 to-purple-600 flex items-center justify-center shadow-glow flex-shrink-0">
                                    <div class="text-center">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-white/70">Antri</p>
                                        <p class="text-lg font-black text-white leading-none"><?= esc($p['nomor_antrian'] ?? '-') ?></p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-base font-extrabold text-white">
                                        <span class="ml-2 text-xs font-semibold text-slate-500"><?= esc($p['kode_pesanan']) ?></span>
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        <i class="fa-solid fa-user mr-1"></i><?= esc($p['nama_pemesan']) ?>
                                        <span class="mx-1.5 text-slate-600">·</span>
                                        <i class="fa-regular fa-clock mr-1"></i><?= date('H:i', strtotime($p['created_at'])) ?>
                                    </p>

                                    <div class="mt-2 flex flex-wrap items-center gap-2">

                                        <span class="inline-flex items-center text-[11px] px-2.5 py-1 rounded-full border bg-white/[.04] text-slate-300 border-white/10">
                                            <i class="fa-solid fa-wallet mr-1.5 text-sky-300"></i>
                                            <?= esc($formatMetode($metodePembayaran)) ?>
                                        </span>

                                        <span class="inline-flex items-center text-[11px] px-2.5 py-1 rounded-full border <?= $statusBadgeClass($statusBayar) ?>">
                                            <?= esc($statusLabel($statusBayar)) ?>
                                        </span>

                                    </div>
                                </div>

                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 xl:justify-end">

                                <span class="text-lg font-extrabold text-white">
                                    <?= $formatRupiah($p['total_harga']) ?>
                                </span>

                                <?php if (! $isLunas): ?>

                                    <form action="<?= base_url('kasir/telah-bayar/' . $p['kode_pesanan']) ?>" method="post" data-bayar-antrian="<?= esc($p['nomor_antrian'] ?? '-') ?>" data-bayar-nama="<?= esc($p['nama_pemesan'] ?? '-') ?>" onsubmit="return konfirmasiBayar(event, this)">
                                        <?= csrf_field() ?>

                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white transition">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                            Telah Bayar
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <div class="flex gap-2">
                                        <button type="button" onclick='cetakStruk(<?= $orderJson ?>)' class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.05] hover:bg-white/[.09] border border-white/10 px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-white transition">
                                            <i class="fa-solid fa-print"></i>
                                            Cetak
                                        </button>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="px-5 py-4">
                            <ul class="space-y-2">

                                                <?php foreach (($p['items'] ?? []) as $item): ?>
                                                    <li class="flex items-start justify-between gap-4 text-sm">

                                                        <span class="flex items-start gap-2 text-slate-300">
                                                            <i class="fa-solid fa-circle-dot text-slate-600 text-[9px] mt-1.5"></i>

                                                            <span>
                                                                <span class="font-bold text-white"><?= esc($item['nama_menu']) ?></span>
                                                                <span class="text-sky-300 font-bold">x<?= esc($item['qty']) ?></span>
                                                            </span>
                                                        </span>

                                                        <span class="text-xs text-slate-500 whitespace-nowrap">
                                                            <?= $formatRupiah(($item['harga'] ?? 0) * ($item['qty'] ?? 0)) ?>
                                                        </span>

                                                    </li>
                                                <?php endforeach; ?>

                            </ul>

                            <?php if (! empty($p['catatan'])): ?>
                                <p class="mt-4 text-xs text-amber-300 bg-amber-500/10 border border-amber-400/20 rounded-2xl px-4 py-3">
                                    <i class="fa-solid fa-note-sticky mr-1.5"></i>
                                    <?= esc($p['catatan']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <div id="empty-state" class="flex flex-col items-center justify-center py-16 text-center <?= ! empty($pesanan_pending) ? 'hidden' : '' ?>">
                <div class="w-16 h-16 rounded-3xl bg-white/[.04] border border-white/10 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-mug-hot text-slate-500 text-2xl"></i>
                </div>

                <p class="text-sm font-bold text-white">Tidak ada pesanan menunggu</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ada pesanan menunggu saat ini.</p>
            </div>

        </div>
    </div>

    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">

        <div class="p-5 lg:p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-white">Riwayat Pesanan Hari Ini</h2>
                <p class="text-xs text-slate-500 mt-1">Pesanan yang sudah masuk hari ini. Struk pesanan yang sudah dibayar dapat dicetak ulang dari sini.</p>
            </div>

            <span class="inline-flex items-center gap-2 text-xs bg-white/[.04] border border-white/10 text-slate-400 px-3 py-2 rounded-2xl">
                <i class="fa-solid fa-calendar-day text-sky-300"></i>
                <?= date('d M Y') ?>
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="border-b border-white/10 bg-white/[.025]">
                    <tr>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Waktu</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Antrian</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Kode</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Pemesan</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Status</th>
                        <th class="text-right text-xs font-bold text-slate-500 uppercase tracking-[0.16em] px-5 py-4">Total</th>
                    </tr>
                </thead>

                <tbody id="riwayat-body" class="divide-y divide-white/10">

                    <?php if (empty($riwayatHariIni)): ?>

                        <tr id="riwayat-empty">
                            <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-500">
                                Belum ada riwayat pesanan hari ini.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($riwayatHariIni as $r): ?>
                            <?php
                            $riwayatJson = json_encode($receiptData($r), $jsonFlags);
                            $statusBayarR = $r['status_pembayaran'] ?? 'pending';
                            ?>

                            <tr id="riwayat-order-<?= esc($r['kode_pesanan']) ?>" class="hover:bg-white/[.025] transition">

                                <td class="px-5 py-4 text-slate-400 whitespace-nowrap">
                                    <?= date('H:i', strtotime($r['created_at'])) ?>
                                </td>

                                <td class="px-5 py-4 font-black text-sky-300 whitespace-nowrap">
                                    <?= esc($r['nomor_antrian'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 font-bold text-white whitespace-nowrap">
                                    <?= esc($r['kode_pesanan']) ?>
                                </td>

                                <td class="px-5 py-4 text-slate-300 whitespace-nowrap">
                                    <?= esc($r['nama_pemesan']) ?>
                                </td>

                                <td class="px-5 py-4 text-right font-extrabold text-white whitespace-nowrap">
                                    <?= $formatRupiah($r['total_harga']) ?>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">

                                    <?php if ($statusBayarR === 'selesai'): ?>
                                        <button type="button" onclick='cetakStruk(<?= $riwayatJson ?>)' class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/20 px-3 py-2 text-xs font-bold text-sky-300 hover:text-white transition">
                                            <i class="fa-solid fa-print"></i>
                                            Cetak
                                        </button>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-600">-</span>
                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

<style>
    .pos-toast {
        pointer-events: auto;
        animation: posToastIn .38s cubic-bezier(.21, 1.02, .73, 1) both;
    }
    .pos-toast.closing {
        animation: posToastOut .3s ease forwards;
    }
    @keyframes posToastIn {
        from { opacity: 0; transform: translateX(28px) scale(.95); }
        to   { opacity: 1; transform: translateX(0) scale(1); }
    }
    @keyframes posToastOut {
        from { opacity: 1; transform: translateX(0) scale(1); }
        to   { opacity: 0; transform: translateX(28px) scale(.95); }
    }
    .pos-toast-progress {
        animation: posToastBar 4.5s linear forwards;
    }
    @keyframes posToastBar {
        from { width: 100%; }
        to   { width: 0%; }
    }
    .pos-toast-check {
        animation: posCheckPop .5s cubic-bezier(.68, -.55, .27, 1.55) both;
        animation-delay: .12s;
    }
    @keyframes posCheckPop {
        from { transform: scale(0) rotate(-30deg); opacity: 0; }
        to   { transform: scale(1) rotate(0); opacity: 1; }
    }
    .pos-confirm-modal {
        animation: posConfirmIn .28s ease both;
    }
    @keyframes posConfirmIn {
        from { opacity: 0; transform: translateY(16px) scale(.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

<div id="pos-toast-container" class="fixed top-5 right-5 z-[80] flex flex-col items-end gap-3 w-[calc(100%-2.5rem)] max-w-sm pointer-events-none"></div>

<div id="pos-confirm-modal" class="fixed inset-0 z-[75] hidden items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="pos-confirm-modal bg-slate-950 border border-white/10 rounded-[2rem] p-6 max-w-sm w-full mx-4 shadow-2xl text-center relative">
        <button type="button" onclick="tutupKonfirmasiBayar()" class="absolute top-4 right-4 w-8 h-8 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-white transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-400/20 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-money-bill-wave text-emerald-300 text-2xl"></i>
        </div>

        <h3 class="text-lg font-extrabold text-white mb-1">Konfirmasi Pembayaran</h3>
        <p class="text-sm text-slate-400 mb-2">Pastikan pembayaran sudah benar-benar diterima dari pelanggan.</p>

        <div class="rounded-2xl bg-white/[.04] border border-white/10 p-4 mb-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Nomor Antrian</p>
            <p id="pos-confirm-antrian" class="text-3xl font-black text-sky-300">-</p>
            <p id="pos-confirm-nama" class="text-xs text-slate-400 mt-1 font-semibold"></p>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button type="button" onclick="tutupKonfirmasiBayar()"
                    class="rounded-2xl bg-white/[.05] hover:bg-white/[.09] border border-white/10 px-4 py-3 text-sm font-bold text-slate-300 hover:text-white transition">
                Batal
            </button>
            <button type="button" id="pos-confirm-ya"
                    class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:opacity-95 px-4 py-3 text-sm font-extrabold text-white shadow-glow transition">
                <i class="fa-solid fa-circle-check mr-1"></i> Ya, Terima
            </button>
        </div>
    </div>
</div>

<div id="modal-notif" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm transition-opacity">

    <div class="bg-slate-950 border border-white/10 rounded-[2rem] p-6 md:p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300" id="modal-box">

        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce bg-sky-500/10 border border-sky-400/20" id="modal-icon-wrap">
            <i class="fa-solid fa-bell text-sky-300 text-3xl" id="modal-icon"></i>
        </div>

        <h3 class="text-xl font-extrabold text-white text-center mb-1" id="modal-title">
            Pesanan Baru!
        </h3>

        <p class="text-slate-400 text-center mb-6 text-sm" id="modal-message">
            Ada pesanan baru masuk.
        </p>

        <div class="bg-white/[.04] rounded-2xl p-4 text-center border border-white/10 flex flex-col items-center">

            <div class="flex gap-1.5 mb-3 mt-1">
                <span class="w-2.5 h-2.5 bg-sky-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                <span class="w-2.5 h-2.5 bg-sky-400 rounded-full animate-bounce" style="animation-delay: .15s"></span>
                <span class="w-2.5 h-2.5 bg-sky-400 rounded-full animate-bounce" style="animation-delay: .3s"></span>
            </div>

            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest" id="modal-subtitle">
                Notifikasi pesanan terbaru.
            </p>

        </div>

    </div>

</div>

<script>
    const STORE_NAME = <?= json_encode($storeNameForReceipt) ?>;

    let lastId = <?= json_encode($last_id ?? '') ?>;
    let lastPaymentTime = '<?= esc($last_payment_time ?? date('Y-m-d H:i:s')) ?>';
    let isCheckingOrder = false;
    let isCheckingPayment = false;
    let csrfHash = '<?= csrf_hash() ?>';
    let csrf_name = '<?= csrf_token() ?>';
    let antreanTTS = [];
    let sedangBicara = false;

    function updateWaktu() {
        const timeEl = document.getElementById('waktu-sekarang');

        if (timeEl) {
            timeEl.textContent = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }

    updateWaktu();
    setInterval(updateWaktu, 1000);

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

    // ------------------------------------------------------------------
    // Toast notifications
    // ------------------------------------------------------------------
    const POS_TOAST_VARIANTS = {
        success: {
            icon: 'fa-circle-check',
            ring: 'border-emerald-400/20',
            iconWrap: 'bg-emerald-500/10 text-emerald-300',
            bar: 'bg-emerald-400',
            accent: 'text-emerald-300',
        },
        error: {
            icon: 'fa-circle-xmark',
            ring: 'border-rose-400/20',
            iconWrap: 'bg-rose-500/10 text-rose-300',
            bar: 'bg-rose-400',
            accent: 'text-rose-300',
        },
        warning: {
            icon: 'fa-triangle-exclamation',
            ring: 'border-amber-400/20',
            iconWrap: 'bg-amber-500/10 text-amber-300',
            bar: 'bg-amber-400',
            accent: 'text-amber-300',
        },
        info: {
            icon: 'fa-circle-info',
            ring: 'border-sky-400/20',
            iconWrap: 'bg-sky-500/10 text-sky-300',
            bar: 'bg-sky-400',
            accent: 'text-sky-300',
        },
    };

    function tampilkanToast(type, opts = {}) {
        const variant = POS_TOAST_VARIANTS[type] || POS_TOAST_VARIANTS.success;
        const durasi = opts.duration || 4500;

        const container = document.getElementById('pos-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className =
            'pos-toast pointer-events-auto flex items-start gap-3 rounded-2xl border bg-slate-950/95 backdrop-blur shadow-2xl p-4 w-full max-w-sm ' + variant.ring;
        toast.innerHTML = `
            <div class="relative">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center ${variant.iconWrap} flex-shrink-0 pos-toast-check">
                    <i class="fa-solid ${variant.icon} text-sm"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-extrabold text-white">${escapeHTML(opts.title || '')}</p>
                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed break-words">${escapeHTML(opts.message || '')}</p>
            </div>
            <button type="button" class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-white transition flex-shrink-0">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
            <div class="absolute bottom-0 left-0 h-0.5 rounded-full ${variant.bar} pos-toast-progress" style="animation-duration:${durasi}ms"></div>
        `;

        toast.style.position = 'relative';
        toast.style.overflow = 'hidden';

        toast.querySelector('button').addEventListener('click', () => tutupToast(toast));

        container.appendChild(toast);

        setTimeout(() => tutupToast(toast), durasi + 250);
    }

    function tutupToast(toast) {
        if (!toast || toast.classList.contains('closing')) return;

        toast.classList.add('closing');
        setTimeout(() => toast.remove(), 300);
    }

    // ------------------------------------------------------------------
    // Konfirmasi + submit "Telah Bayar"
    // ------------------------------------------------------------------
    let bayarFormPending = null;

    function konfirmasiBayar(event, form) {
        event.preventDefault();

        bayarFormPending = form;

        const antrian = form.dataset.bayarAntrian || '-';
        const nama = form.dataset.bayarNama || '-';

        document.getElementById('pos-confirm-antrian').textContent = antrian;
        document.getElementById('pos-confirm-nama').textContent = nama ? 'Atas nama ' + nama : '';

        const modal = document.getElementById('pos-confirm-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        return false;
    }

    function tutupKonfirmasiBayar() {
        const modal = document.getElementById('pos-confirm-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        bayarFormPending = null;
    }

    document.getElementById('pos-confirm-ya')?.addEventListener('click', function () {
        if (!bayarFormPending) return;

        const form = bayarFormPending;
        const btn = form.querySelector('button[type="submit"]');

        tutupKonfirmasiBayar();
        submitTelahBayar(form, btn);
    });

    async function submitTelahBayar(form, btn) {
        const antrian = form.dataset.bayarAntrian || '-';

        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span> Memproses...';

        try {
            const formData = new FormData(form);
            formData.append(csrf_name, csrfHash);

            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (data.success) {
                if (data.data) {
                    if (data.data.updated_at) {
                        lastPaymentTime = data.data.updated_at;
                    }

                    const card = document.querySelector(`[data-order-card="${data.data.kode_pesanan}"]`);

                    if (card) {
                        const wasUnpaid = card.querySelector('button[type="submit"]') !== null;

                        if (wasUnpaid) {
                            tambahPendapatan(data.data);
                            tambahRiwayat(data.data);

                            card.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                            setTimeout(() => {
                                card.remove();
                                updateEmptyState();
                            }, 500);

                            const countPending = document.getElementById('count-pending');
                            if (countPending) countPending.textContent = Math.max(0, parseInt(countPending.textContent || '0') - 1);

                            const countSelesai = document.getElementById('count-selesai');
                            if (countSelesai) countSelesai.textContent = parseInt(countSelesai.textContent || '0') + 1;
                        } else {
                            card.outerHTML = renderOrderHTML(data.data);
                        }
                    } else {
                        tambahRiwayat(data.data);
                    }
                }

                tampilkanToast('success', {
                    title: 'Pembayaran Diterima',
                    message: `Pembayaran antrian ${antrian} sudah tercatat.`,
                    duration: 4500,
                });
            } else {
                tampilkanToast('error', {
                    title: 'Gagal',
                    message: data.message || 'Terjadi kesalahan saat memproses pembayaran.',
                });
            }
        } catch (err) {
            tampilkanToast('error', {
                title: 'Gagal Terhubung',
                message: 'Terjadi kesalahan komunikasi dengan server: ' + (err.message || ''),
            });
        }

        if (btn.isConnected) {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    }

    function tambahPendapatan(p) {
        const pendapatanEl = document.getElementById('pendapatan-hari-ini');

        if (!pendapatanEl || !p.total_harga) return;

        const oldAmount = parseInt(pendapatanEl.dataset.amount || '0');
        const addAmount = parseInt(p.total_harga || '0');
        const newAmount = oldAmount + addAmount;

        pendapatanEl.dataset.amount = newAmount;
        pendapatanEl.textContent = 'Rp ' + formatRupiah(newAmount);
    }

    function formatStatusLabel(status) {
        if (status === 'selesai') return 'Pembayaran Selesai';
        if (status === 'pending') return 'Menunggu Bayar';

        return status ? status.charAt(0).toUpperCase() + status.slice(1) : '-';
    }

    function statusBadgeClass(status) {
        if (status === 'selesai') return 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20';
        if (status === 'pending') return 'bg-amber-500/10 text-amber-300 border-amber-400/20';

        return 'bg-slate-500/10 text-slate-300 border-slate-400/20';
    }

    function formatStatusPesananLabel(status) {
        if (status === 'siap') return 'Siap Diantarkan';
        if (status === 'selesai') return 'Selesai';
        if (status === 'menunggu') return 'Sedang Disiapkan';

        return status ? status.charAt(0).toUpperCase() + status.slice(1) : '-';
    }

    function statusPesananBadgeClass(status) {
        if (status === 'siap') return 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20';
        if (status === 'selesai') return 'bg-sky-500/10 text-sky-300 border-sky-400/20';
        if (status === 'menunggu') return 'bg-amber-500/10 text-amber-300 border-amber-400/20';

        return 'bg-slate-500/10 text-slate-300 border-slate-400/20';
    }

    function formatMetodeLabel(metode) {
        if (metode === 'tunai') return 'Tunai / COD';
        if (metode === 'qris') return 'QRIS / E-Wallet';
        if (metode === 'transfer') return 'Transfer Bank / VA';

        return metode || '-';
    }

    function tutupModal() {
        const modal = document.getElementById('modal-notif');
        const box = document.getElementById('modal-box');

        if (modal && !modal.classList.contains('hidden')) {
            box.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function tampilkanModal(type, data) {
        const modal = document.getElementById('modal-notif');
        const box = document.getElementById('modal-box');
        const title = document.getElementById('modal-title');
        const message = document.getElementById('modal-message');
        const subtitle = document.getElementById('modal-subtitle');
        const icon = document.getElementById('modal-icon');
        const iconWrap = document.getElementById('modal-icon-wrap');

        if (!modal || !box || !title || !message || !subtitle || !icon || !iconWrap) return;

        iconWrap.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce border';

        if (type === 'pembayaran') {
            iconWrap.classList.add('bg-emerald-500/10', 'border-emerald-400/20');
            icon.className = 'fa-solid fa-money-bill-wave text-emerald-300 text-3xl';
            title.textContent = 'Pembayaran Berhasil!';

            message.innerHTML = `
                Pembayaran pesanan <span class="font-bold text-emerald-300">${escapeHTML(data.nomor_antrian || '-')}</span>
                atas nama <span class="font-bold text-white">${escapeHTML(data.nama)}</span> sudah selesai.
            `;

            subtitle.textContent = 'Notifikasi pembayaran berhasil.';

        } else {
            iconWrap.classList.add('bg-sky-500/10', 'border-sky-400/20');
            icon.className = 'fa-solid fa-bell text-sky-300 text-3xl';
            title.textContent = 'Pesanan Baru!';

            message.innerHTML = `
                Pesanan <span class="font-bold text-sky-300">${escapeHTML(data.nomor_antrian || '-')}</span>
                atas nama <span class="font-bold text-white">${escapeHTML(data.nama)}</span>.
            `;

            subtitle.textContent = 'Notifikasi pesanan baru.';
        }

        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                box.classList.remove('scale-95');
            }, 50);
        }
    }

    function prosesAntreanNotifikasi() {
        if (antreanTTS.length === 0) {
            sedangBicara = false;
            setTimeout(tutupModal, 1000);
            return;
        }

        sedangBicara = true;

        const current = antreanTTS.shift();

        tampilkanModal(current.type, current);

        setTimeout(prosesAntreanNotifikasi, 2500);
    }

    function normalizeReceiptData(p) {
        const items = Array.isArray(p.items) ? p.items : [];

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
            id: p.kode_pesanan || '-',
            kode_pesanan: p.kode_pesanan || '-',
            nomor_antrian: p.nomor_antrian || '-',
            nama_pemesan: p.nama_pemesan || '-',
            metode_pembayaran: p.metode_pembayaran || '-',
            metode_label: p.metode_label || formatMetodeLabel(p.metode_pembayaran || '-'),
            status: p.status_pembayaran || 'pending',
            status_pesanan: p.status_pesanan || 'menunggu',
            catatan: p.catatan || '',
            waktu: p.waktu || p.created_waktu || '-',
            total_harga: total,
            items: items
        };
    }

    function cetakStruk(rawData) {
        const data = normalizeReceiptData(rawData);

        let itemsHTML = '';

        data.items.forEach(item => {
            const subtotal = parseInt(item.harga || 0) * parseInt(item.qty || 0);

            itemsHTML += `
                <div class="item">
                    <div>
                        <strong>${escapeHTML(item.nama_menu)}</strong>
                        <small>${escapeHTML(item.qty)} x Rp ${formatRupiah(item.harga)}</small>
                    </div>
                    <span>Rp ${formatRupiah(subtotal)}</span>
                </div>
            `;
        });

        const catatanHTML = data.catatan ?
            `
                <div class="note">
                    <strong>Catatan:</strong><br>
                    ${escapeHTML(data.catatan)}
                </div>
            ` :
            '';

        const html = `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Struk ${escapeHTML(data.kode_pesanan)}</title>

                <style>
                    * {
                        box-sizing: border-box;
                    }

                    body {
                        margin: 0;
                        padding: 18px;
                        color: #111;
                        background: #fff;
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                    }

                    .receipt {
                        width: 100%;
                        max-width: 320px;
                        margin: 0 auto;
                    }

                    .center {
                        text-align: center;
                    }

                    .store {
                        font-size: 20px;
                        font-weight: 900;
                        margin-bottom: 3px;
                    }

                    .subtitle {
                        color: #555;
                        font-size: 10px;
                    }

                    .queue {
                        margin: 15px 0;
                        padding: 12px;
                        text-align: center;
                        border: 2px dashed #111;
                    }

                    .queue span {
                        display: block;
                        font-size: 10px;
                        font-weight: 700;
                        text-transform: uppercase;
                    }

                    .queue strong {
                        display: block;
                        margin-top: 4px;
                        font-size: 34px;
                        font-weight: 900;
                    }

                    .line {
                        margin: 12px 0;
                        border-top: 1px dashed #777;
                    }

                    .row {
                        display: flex;
                        justify-content: space-between;
                        gap: 12px;
                        margin-bottom: 5px;
                    }

                    .label {
                        color: #555;
                    }

                    .value {
                        text-align: right;
                        font-weight: 700;
                    }

                    .item {
                        display: flex;
                        justify-content: space-between;
                        gap: 12px;
                        margin-bottom: 9px;
                    }

                    .item strong {
                        display: block;
                        font-size: 11px;
                    }

                    .item small {
                        display: block;
                        margin-top: 2px;
                        color: #666;
                        font-size: 9px;
                    }

                    .note {
                        margin-top: 10px;
                        padding: 8px;
                        border: 1px dashed #777;
                        font-size: 10px;
                    }

                    .total-row {
                        font-size: 15px;
                        font-weight: 900;
                    }

                    .thanks {
                        margin-top: 15px;
                        text-align: center;
                        color: #555;
                        font-size: 10px;
                        line-height: 1.5;
                    }

                    @media print {
                        body {
                            padding: 0;
                        }

                        @page {
                            margin: 5mm;
                        }
                    }
                </style>
            </head>

            <body>
                <div class="receipt">

                    <div class="center">
                        <div class="store">${escapeHTML(STORE_NAME)}</div>
                        <div class="subtitle">Struk Pesanan</div>
                    </div>

                    <div class="queue">
                        <span>Nomor Antrian</span>
                        <strong>${escapeHTML(data.nomor_antrian)}</strong>
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
                        Simpan struk ini sebagai bukti transaksi.
                    </div>

                </div>

                <script>
                    window.onload = function () {
                        setTimeout(function () {
                            window.print();

                            setTimeout(function () {
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

    function renderOrderHTML(p) {
        let itemsHTML = '';

        if (Array.isArray(p.items)) {
            p.items.forEach(item => {
                const subtotal = parseInt(item.harga || 0) * parseInt(item.qty || 0);

                itemsHTML += `
                    <li class="flex items-start justify-between gap-4 text-sm">
                        <span class="flex items-start gap-2 text-slate-300">
                            <i class="fa-solid fa-circle-dot text-slate-600 text-[9px] mt-1.5"></i>

                            <span>
                                <span class="font-bold text-white">${escapeHTML(item.nama_menu)}</span>
                                <span class="text-sky-300 font-bold">x${escapeHTML(item.qty)}</span>
                            </span>
                        </span>

                        <span class="text-xs text-slate-500 whitespace-nowrap">
                            Rp ${formatRupiah(subtotal)}
                        </span>
                    </li>
                `;
            });
        }

        const catUmum = p.catatan ?
            `
                <p class="mt-4 text-xs text-amber-300 bg-amber-500/10 border border-amber-400/20 rounded-2xl px-4 py-3">
                    <i class="fa-solid fa-note-sticky mr-1.5"></i>
                    ${escapeHTML(p.catatan)}
                </p>
            ` :
            '';

        const metode = String(p.metode_pembayaran || '').toLowerCase();
        const metodeLabel = p.metode_label || formatMetodeLabel(metode);
        const status = p.status || p.status_pembayaran || 'pending';
        const statusPesanan = p.status_pesanan || 'menunggu';

        const isLunas = status === 'selesai';

        const receiptPayload = JSON.stringify(p).replaceAll("'", '&#039;');

        let actionHTML = '';

        if (!isLunas) {
            actionHTML = `
                <form action="<?= base_url('kasir/telah-bayar/') ?>${escapeHTML(p.kode_pesanan)}" method="post" data-bayar-antrian="${escapeHTML(p.nomor_antrian || '-')}" data-bayar-nama="${escapeHTML(p.nama_pemesan || '-')}" onsubmit="return konfirmasiBayar(event, this)">
                    <input type="hidden" name="<?= csrf_token() ?>" value="${csrfHash}">

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white transition">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        Telah Bayar
                    </button>
                </form>
            `;

        } else {
            actionHTML = `
                <div class="flex gap-2">
                    <button type="button" onclick='cetakStruk(${receiptPayload})' class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.05] hover:bg-white/[.09] border border-white/10 px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-white transition">
                        <i class="fa-solid fa-print"></i>
                        Cetak
                    </button>
                </div>
            `;
        }

        return `
            <div class="rounded-[1.5rem] border border-sky-400/20 bg-sky-500/[.05] overflow-hidden shadow-soft" data-order-card="${escapeHTML(p.kode_pesanan)}">

                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 px-5 py-4 border-b border-white/10 bg-white/[.025]">

                    <div class="flex items-start gap-4">

                        <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-sky-500 to-purple-600 flex items-center justify-center shadow-glow flex-shrink-0">
                            <div class="text-center">
                                <p class="text-[9px] font-black uppercase tracking-widest text-white/70">Antri</p>
                                <p class="text-lg font-black text-white leading-none">${escapeHTML(p.nomor_antrian || '-')}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-base font-extrabold text-white">
                                <span class="ml-2 text-xs font-semibold text-slate-500">${escapeHTML(p.kode_pesanan)}</span>
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                <i class="fa-solid fa-user mr-1"></i>${escapeHTML(p.nama_pemesan)}
                                <span class="mx-1.5 text-slate-600">·</span>
                                <i class="fa-regular fa-clock mr-1"></i>${escapeHTML(p.waktu || '-')}
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-2">

                                <span class="inline-flex items-center text-[11px] px-2.5 py-1 rounded-full border bg-white/[.04] text-slate-300 border-white/10">
                                    <i class="fa-solid fa-wallet mr-1.5 text-sky-300"></i>
                                    ${escapeHTML(metodeLabel)}
                                </span>

                                <span class="inline-flex items-center text-[11px] px-2.5 py-1 rounded-full border ${statusBadgeClass(status)}">
                                    ${escapeHTML(formatStatusLabel(status))}
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 xl:justify-end">

                        <span class="text-lg font-extrabold text-white">
                            Rp ${escapeHTML(p.total_format || formatRupiah(p.total_harga))}
                        </span>

                        ${actionHTML}

                    </div>

                </div>

                <div class="px-5 py-4">
                    <ul class="space-y-2">${itemsHTML}</ul>
                    ${catUmum}
                </div>

            </div>
        `;
    }

    function renderRiwayatHTML(p) {
        const statusBayar = p.status_pembayaran || 'pending';
        const waktu = p.created_waktu || p.waktu || '-';
        const totalFormat = p.total_format || formatRupiah(p.total_harga || 0);
        const receiptPayload = JSON.stringify(p).replaceAll("'", '&#039;');

        const printButton = statusBayar === 'selesai' ?
            `
                <button type="button" onclick='cetakStruk(${receiptPayload})' class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/20 px-3 py-2 text-xs font-bold text-sky-300 hover:text-white transition">
                    <i class="fa-solid fa-print"></i>
                    Cetak
                </button>
            ` :
            `<span class="text-xs text-slate-600">-</span>`;

        return `
            <tr id="riwayat-order-${escapeHTML(p.kode_pesanan)}" class="hover:bg-white/[.025] bg-emerald-500/[.025] transition">

                <td class="px-5 py-4 text-slate-400 whitespace-nowrap">${escapeHTML(waktu)}</td>

                <td class="px-5 py-4 font-black text-sky-300 whitespace-nowrap">
                    ${escapeHTML(p.nomor_antrian || '-')}
                </td>

                <td class="px-5 py-4 font-bold text-white whitespace-nowrap">
                    ${escapeHTML(p.kode_pesanan)}
                </td>

                <td class="px-5 py-4 text-slate-300 whitespace-nowrap">
                    ${escapeHTML(p.nama_pemesan)}
                </td>

                <td class="px-5 py-4 text-right font-extrabold text-white whitespace-nowrap">
                    Rp ${escapeHTML(totalFormat)}
                </td>

                <td class="px-5 py-4 text-right whitespace-nowrap">
                    ${printButton}
                </td>

            </tr>
        `;
    }

    function updateEmptyState() {
        const container = document.getElementById('order-container');
        const emptyState = document.getElementById('empty-state');
        const countPending = document.getElementById('count-pending');

        const total = countPending ? parseInt(countPending.textContent || '0') : 0;

        if (total <= 0) {
            if (container) container.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
        } else {
            if (container) container.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
        }
    }

    function tambahRiwayat(p) {
        const tbody = document.getElementById('riwayat-body');
        const empty = document.getElementById('riwayat-empty');

        if (!tbody) return false;

        if (empty) empty.remove();

        const existing = document.getElementById('riwayat-order-' + p.kode_pesanan);

        if (existing) {
            existing.remove();
        }

        tbody.insertAdjacentHTML('afterbegin', renderRiwayatHTML(p));

        return !existing;
    }

    function tanganiPesananBaru(pesananList) {
        try {
            const container = document.getElementById('order-container');
            const emptyState = document.getElementById('empty-state');

            if (emptyState) emptyState.classList.add('hidden');
            if (container) container.classList.remove('hidden');

            pesananList.forEach(p => {
                const existingCard = document.querySelector(`[data-order-card="${p.kode_pesanan}"]`);

                if (!existingCard && container) {
                    container.insertAdjacentHTML('afterbegin', renderOrderHTML(p));

                    const counter = document.getElementById('count-pending');

                    if (counter) {
                        counter.textContent = parseInt(counter.textContent || '0') + 1;
                    }
                }

                tambahRiwayat(p);

                antreanTTS.push({
                    type: 'pesanan',
                    nomor_antrian: p.nomor_antrian || '-',
                    nama: p.nama_pemesan,
                    metode: p.metode_label || formatMetodeLabel(p.metode_pembayaran || ''),
                    total: p.total_harga || 0
                });
            });

            updateEmptyState();

            if (!sedangBicara) {
                prosesAntreanNotifikasi();
            }

        } catch (err) {
            console.error('Terjadi kesalahan tampilan:', err);
            setTimeout(tutupModal, 3000);
        }
    }

    function tanganiPembayaranBerhasil(pesananList) {
        try {
            pesananList.forEach(p => {
                const card = document.querySelector(`[data-order-card="${p.kode_pesanan}"]`);

                if (card) {
                    card.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        card.remove();
                        updateEmptyState();
                    }, 500);

                    const countPending = document.getElementById('count-pending');
                    if (countPending) countPending.textContent = Math.max(0, parseInt(countPending.textContent || '0') - 1);

                    const countSelesai = document.getElementById('count-selesai');
                    if (countSelesai) countSelesai.textContent = parseInt(countSelesai.textContent || '0') + 1;
                }

                const isNewHistory = tambahRiwayat(p);

                if (isNewHistory) {
                    const pendapatanEl = document.getElementById('pendapatan-hari-ini');

                    if (pendapatanEl) {
                        const oldAmount = parseInt(pendapatanEl.dataset.amount || '0');
                        const addAmount = parseInt(p.total_harga || '0');
                        const newAmount = oldAmount + addAmount;

                        pendapatanEl.dataset.amount = newAmount;
                        pendapatanEl.textContent = 'Rp ' + formatRupiah(newAmount);
                    }
                }

                antreanTTS.push({
                    type: 'pembayaran',
                    nomor_antrian: p.nomor_antrian || '-',
                    nama: p.nama_pemesan,
                    metode: p.metode_label || formatMetodeLabel(p.metode_pembayaran || ''),
                    total: p.total_harga || 0
                });
            });

            updateEmptyState();

            if (!sedangBicara) {
                prosesAntreanNotifikasi();
            }

        } catch (err) {
            console.error('Terjadi kesalahan saat menangani pembayaran berhasil:', err);
            setTimeout(tutupModal, 3000);
        }
    }

    function cekPesananBaru() {
        if (isCheckingOrder) return;

        isCheckingOrder = true;

        const url = '<?= base_url('kasir/cek_baru') ?>/' + lastId + '?t=' + Date.now();

        fetch(url, {
                method: 'GET',
                cache: 'no-store',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal terhubung ke server');

                return res.json();
            })
            .then(data => {
                if (data.ada && data.data && data.data.length > 0) {
                    lastId = data.last_id;
                    tanganiPesananBaru(data.data);
                }

                isCheckingOrder = false;
            })
            .catch(err => {
                console.error('Cek pesanan baru gagal:', err);
                isCheckingOrder = false;
            });
    }

    function cekPembayaranBerhasil() {
        if (isCheckingPayment) return;

        isCheckingPayment = true;

        const url = '<?= base_url('kasir/cek_pembayaran_berhasil') ?>' +
            '?last_time=' + encodeURIComponent(lastPaymentTime) +
            '&t=' + Date.now();

        fetch(url, {
                method: 'GET',
                cache: 'no-store',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal cek pembayaran');

                return res.json();
            })
            .then(data => {
                if (data.last_time) {
                    lastPaymentTime = data.last_time;
                }

                if (data.ada && data.data && data.data.length > 0) {
                    tanganiPembayaranBerhasil(data.data);
                }

                isCheckingPayment = false;
            })
            .catch(err => {
                console.error('Cek pembayaran berhasil gagal:', err);
                isCheckingPayment = false;
            });
    }

    if (typeof POS_FLASH_SUCCESS !== 'undefined') {
        tampilkanToast('success', { title: 'Berhasil', message: POS_FLASH_SUCCESS, duration: 5000 });
    }

    if (typeof POS_FLASH_ERROR !== 'undefined') {
        tampilkanToast('error', { title: 'Perhatian', message: POS_FLASH_ERROR, duration: 6000 });
    }

    async function ubahStatusPesanan(id, status) {
        // Obsolete function
    }

    setInterval(cekPesananBaru, 3000);
    setInterval(cekPembayaranBerhasil, 3000);
</script>

<?= $this->endSection() ?>