<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
helper('app_setting');
$storeNameForPos = app_setting('store_name', 'Nama Toko');

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
        'selesai' => 'Selesai',
        'pending' => 'Pending',
        default => ucfirst((string) $status),
    };
};

$formatRupiah = static function ($value): string {
    return 'Rp ' . number_format((int) $value, 0, ',', '.');
};

$receiptData = static function (array $p) use ($formatMetode): array {
    $items = [];
    foreach (($p['items'] ?? []) as $item) {
        $items[] = [
            'nama_menu' => $item['nama_menu'] ?? '-',
            'harga' => (int)($item['harga'] ?? 0),
            'qty' => (int)($item['qty'] ?? 0),
        ];
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
        'total_harga' => (int)($p['total_harga'] ?? 0),
        'items' => $items,
    ];
};
?>

<div class="space-y-6">
    <!-- Header with Switch -->
    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft p-4 lg:p-6">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-sky-500/20 blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl lg:text-2xl font-extrabold tracking-tight text-white flex items-center gap-3">
                    <span id="header-title">POS Pintar</span>
                    <span id="live-indicator" class="hidden inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Live
                    </span>
                </h1>
                <p id="header-desc" class="text-xs text-slate-500 mt-1">Pilih menu dan buat pesanan pelanggan.</p>
            </div>

            <!-- Tab Switcher -->
            <div class="flex p-1 bg-white/[.04] border border-white/10 rounded-2xl w-fit">
                <button onclick="switchTab('pos')" id="btn-tab-pos" class="tab-btn px-6 py-2.5 rounded-xl text-xs font-bold transition-all bg-sky-500 text-white shadow-glow">
                    <i class="fa-solid fa-cash-register mr-2"></i> POS
                </button>
                <button onclick="switchTab('antrean')" id="btn-tab-antrean" class="tab-btn px-6 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white">
                    <i class="fa-solid fa-bell-concierge mr-2"></i> Antrean 
                    <span id="badge-pending-count" class="ml-1 px-1.5 py-0.5 rounded-md bg-white/10 text-[10px]"><?= $total_pending ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- TAB POS -->
    <div id="tab-pos" class="tab-content block">
        <div class="flex flex-col lg:flex-row gap-4 h-[calc(100vh-250px)] min-h-[500px]">
            <!-- Menu Grid -->
            <div class="flex-1 min-w-0 flex flex-col">
                <div class="relative mb-4">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    <input type="text" id="pos-search" placeholder="Cari menu..." class="w-full bg-white/[.04] border border-white/10 text-white rounded-2xl pl-11 pr-4 py-3 text-sm placeholder-slate-500 outline-none focus:border-sky-400/40 transition">
                </div>
                <div id="pos-menu-grid" class="flex-1 overflow-y-auto pr-1 scrollbar-thin">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 pb-4">
                        <?php foreach (($menus ?? []) as $item): 
                            $stokRaw = $item['stok'] ?? null;
                            $isHabis = (!is_null($stokRaw) && (int)$stokRaw <= 0) || !((int)($item['is_active'] ?? 1));
                            $isLow = !is_null($stokRaw) && (int)$stokRaw > 0 && (int)$stokRaw <= 10;
                            $stokLabel = is_null($stokRaw) ? 'Unlimited' : (string)(int)$stokRaw;
                        ?>
                            <div class="pos-menu-card group relative rounded-2xl border overflow-hidden cursor-pointer transition <?= $isHabis ? 'opacity-50 pointer-events-none border-white/10 bg-white/[.04]' : ($isLow ? 'border-amber-400/30 bg-amber-500/[.06] hover:border-amber-400/50' : 'border-white/10 bg-white/[.04] hover:border-sky-400/30 hover:bg-sky-500/[.04]') ?>"
                                 data-id="<?= esc($item['id']) ?>" data-nama="<?= esc($item['nama_menu']) ?>" data-harga="<?= esc($item['harga']) ?>" data-stok="<?= esc(is_null($stokRaw) ? 'null' : (int)$stokRaw) ?>" onclick="posAddToCart(this)">
                                <div class="relative aspect-[4/3] bg-white/[.03]">
                                    <?php if (!empty($item['gambar'])): ?>
                                        <img src="<?= base_url('uploads/menu/' . $item['gambar']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-slate-600"><i class="fa-solid fa-utensils text-2xl"></i></div>
                                    <?php endif; ?>
                                    <div id="pos-stok-badge-wrap-<?= esc($item['id']) ?>" class="absolute top-2 left-2 flex flex-col gap-1.5 z-10">
                                        <?php if ($isHabis): ?>
                                            <span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-1 rounded-full bg-rose-500 text-white shadow"><i class="fa-solid fa-xmark text-[8px]"></i> Habis</span>
                                        <?php elseif ($isLow): ?>
                                            <span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-1 rounded-full bg-amber-500 text-white shadow animate-pulse"><i class="fa-solid fa-triangle-exclamation text-[8px]"></i> Stok menipis · <?= esc($stokLabel) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div id="pos-qty-<?= esc($item['id']) ?>" class="pos-qty-badge absolute top-2 right-2 hidden items-center justify-center min-w-[28px] h-7 rounded-full bg-sky-500 text-white text-xs font-black shadow-lg">0</div>
                                </div>
                                <div class="p-3">
                                    <h4 class="text-[11px] font-extrabold text-white leading-tight line-clamp-2 min-h-[28px]"><?= esc($item['nama_menu']) ?></h4>
                                    <p class="text-xs font-black text-sky-300 mt-1">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                    <p id="pos-stok-text-<?= esc($item['id']) ?>" class="text-[10px] font-bold mt-1 flex items-center gap-1 <?= is_null($stokRaw) ? 'hidden' : ($isHabis ? 'text-rose-400' : ($isLow ? 'text-amber-300' : 'text-slate-500')) ?>">
                                        <?php if (is_null($stokRaw)): ?>
                                        <?php elseif ($isHabis): ?><i class="fa-solid fa-circle-xmark text-[9px]"></i> Stok Habis
                                        <?php elseif ($isLow): ?><i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Sisa stok <?= esc($stokLabel) ?>
                                        <?php else: ?>Stok <?= esc($stokLabel) ?><?php endif; ?>
                                    </p>
                                </div>
                                <div id="pos-stok-bar-<?= esc($item['id']) ?>" class="h-1 w-full bg-amber-500/60 <?= $isLow ? '' : 'hidden' ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Cart Sidebar -->
            <div class="w-full lg:w-[380px] flex-shrink-0 flex flex-col rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden">
                <div class="p-4 border-b border-white/10 bg-white/[.025] flex items-center justify-between">
                    <h2 class="text-sm font-extrabold text-white"><i class="fa-solid fa-cart-shopping mr-2 text-sky-400"></i>Keranjang</h2>
                    <button onclick="posClearCart()" class="text-rose-400 hover:text-rose-300 transition text-xs"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                <div id="pos-cart-body" class="flex-1 overflow-y-auto p-4 space-y-2">
                    <div id="pos-cart-empty" class="flex flex-col items-center justify-center h-full text-center opacity-50 py-10">
                        <i class="fa-solid fa-basket-shopping text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Kosong</p>
                    </div>
                    <div id="pos-cart-items"></div>
                </div>
            <div class="p-4 border-t border-white/10 bg-white/[.025] space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] mb-1.5">Nama Pelanggan *</label>
                    <input type="text" id="pos-nama" placeholder="Nama pelanggan" maxlength="100" class="w-full bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none focus:border-sky-400/40 transition placeholder-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] mb-1.5">Metode Pembayaran *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 rounded-xl bg-white/[.04] border border-white/10 px-3 py-2.5 cursor-pointer transition hover:border-sky-400/30 has-[:checked]:border-sky-400/50 has-[:checked]:bg-sky-500/[.08]">
                            <input type="radio" name="pos_payment_method" value="tunai" checked class="hidden" onchange="posHitungKembalian()">
                            <i class="fa-solid fa-money-bill-wave text-xs text-slate-400"></i>
                            <span class="text-[11px] font-bold text-slate-300">Tunai</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 rounded-xl bg-white/[.04] border border-white/10 px-3 py-2.5 cursor-pointer transition hover:border-sky-400/30 has-[:checked]:border-sky-400/50 has-[:checked]:bg-sky-500/[.08]">
                            <input type="radio" name="pos_payment_method" value="qris" class="hidden" onchange="posHitungKembalian()">
                            <i class="fa-solid fa-qrcode text-xs text-slate-400"></i>
                            <span class="text-[11px] font-bold text-slate-300">QRIS</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] mb-1.5">Catatan (opsional)</label>
                    <input type="text" id="pos-catatan" placeholder="Catatan pesanan..." maxlength="500" class="w-full bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none focus:border-sky-400/40 transition placeholder-slate-600">
                </div>
                    <!-- Uang Bayar (Tunai Only) -->
                    <div id="pos-uang-bayar-section" class="">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] mb-1.5">Uang Bayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500 font-semibold">Rp</span>
                            <input type="number"
                                   id="pos-uang-bayar"
                                   placeholder="0"
                                   min="0"
                                   oninput="posHitungKembalian()"
                                   class="w-full bg-white/[.04] border border-white/10 text-white text-xs rounded-xl pl-9 pr-3 py-2.5 outline-none focus:border-sky-400/40 transition placeholder-slate-600">
                        </div>
                        <div id="pos-kembalian-display" class="mt-2 rounded-xl bg-emerald-500/10 border border-emerald-400/20 px-3 py-2 hidden">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Kembalian</span>
                                <span id="pos-kembalian" class="text-sm font-black text-emerald-300">Rp 0</span>
                            </div>
                        </div>
                        <div id="pos-kurang-display" class="mt-2 rounded-xl bg-rose-500/10 border border-rose-400/20 px-3 py-2 hidden">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-rose-300 uppercase tracking-wider">Kurang</span>
                                <span id="pos-kurang" class="text-sm font-black text-rose-300">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-xs font-bold text-slate-400">Total</span>
                        <span id="pos-total" class="text-lg font-black text-white">Rp 0</span>
                    </div>
                    <button id="pos-btn-submit" onclick="posSubmitOrder()" disabled class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 py-3.5 text-sm font-extrabold text-white shadow-glow transition hover:opacity-95 disabled:opacity-30">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB ANTREAN -->
    <div id="tab-antrean" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="rounded-2xl border border-white/10 bg-white/[.035] p-5">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menunggu</p>
                <p class="text-2xl font-black text-white mt-1" id="count-pending"><?= $total_pending ?></p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[.035] p-5">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pendapatan Hari Ini</p>
                <p class="text-2xl font-black text-emerald-400 mt-1" id="pendapatan-hari-ini" data-amount="<?= (int)$pendapatan ?>"><?= $formatRupiah($pendapatan) ?></p>
            </div>
        </div>

        <div id="order-container" class="space-y-4">
            <?php foreach (($pesanan_pending ?? []) as $p): ?>
                <div class="rounded-[1.5rem] border border-amber-400/20 bg-amber-500/[.03] overflow-hidden shadow-soft" data-order-card="<?= esc($p['kode_pesanan']) ?>">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-5 py-4 border-b border-white/10 bg-white/[.02]">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-purple-600 flex items-center justify-center font-black text-white shadow-glow"><?= esc($p['nomor_antrian']) ?></div>
                            <div>
                                <p class="text-sm font-extrabold text-white"><?= esc($p['nama_pemesan']) ?> <span class="text-[10px] text-slate-500 ml-2">#<?= esc($p['kode_pesanan']) ?></span></p>
                                <p class="text-[10px] text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i><?= date('H:i', strtotime($p['created_at'])) ?> · <?= esc($formatMetode($p['metode_pembayaran'])) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black text-white"><?= $formatRupiah($p['total_harga']) ?></span>
                            <button type="button" onclick="konfirmasiBatal('<?= esc($p['kode_pesanan']) ?>','<?= esc($p['nomor_antrian']) ?>')" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-rose-500/20 border border-white/10 hover:border-rose-400/30 flex items-center justify-center text-slate-400 hover:text-rose-300 transition" title="Batalkan pesanan"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            <form action="<?= base_url('kasir/telah-bayar/' . $p['kode_pesanan']) ?>" method="post" data-bayar-antrian="<?= esc($p['nomor_antrian']) ?>" data-bayar-nama="<?= esc($p['nama_pemesan']) ?>" onsubmit="return konfirmasiBayar(event, this)">
                                <?= csrf_field() ?>
                                <button type="submit" class="rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition shadow-glow">Telah Bayar</button>
                            </form>
                        </div>
                    </div>
                    <div class="px-5 py-3">
                        <ul class="text-xs space-y-1 text-slate-400">
                            <?php foreach ($p['items'] as $it): ?>
                                <li class="flex justify-between"><span><?= esc($it['nama_menu']) ?> <b class="text-sky-400 ml-1">x<?= $it['qty'] ?></b></span> <span><?= $formatRupiah($it['sub_total']) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="empty-state" class="py-20 text-center <?= !empty($pesanan_pending) ? 'hidden' : '' ?>">
            <i class="fa-solid fa-mug-hot text-4xl text-slate-700 mb-3"></i>
            <p class="text-sm font-bold text-slate-500">Antrean kosong</p>
        </div>
    </div>
</div>

<div id="pos-batal-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-white/10 rounded-[2rem] p-6 max-w-sm w-full text-center shadow-2xl">
        <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-400 flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa-solid fa-trash-can"></i></div>
        <h3 class="text-lg font-bold text-white">Batalkan Pesanan?</h3>
        <p class="text-xs text-slate-400 mt-1 mb-4">Pesanan <span id="pos-batal-antrian" class="font-bold text-white">-</span> akan dihapus. Stok dikembalikan & tidak masuk riwayat.</p>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="tutupKonfirmasiBatal()" class="py-3 rounded-xl bg-white/5 text-xs font-bold">Batal</button>
            <button id="pos-batal-ya" class="py-3 rounded-xl bg-rose-500 text-xs font-bold text-white shadow-glow">Ya, Batalkan</button>
        </div>
    </div>
</div>

<div id="pos-clear-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-white/10 rounded-[2rem] p-6 max-w-sm w-full text-center shadow-2xl">
        <div class="w-16 h-16 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa-solid fa-trash-can"></i></div>
        <h3 class="text-lg font-bold text-white">Kosongkan Keranjang?</h3>
        <p class="text-xs text-slate-400 mt-1 mb-4"><span id="pos-clear-count">0</span> item akan dihapus. Tindakan tidak bisa dibatalkan.</p>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="tutupKonfirmasiClear()" class="py-3 rounded-xl bg-white/5 text-xs font-bold">Batal</button>
            <button id="pos-clear-ya" class="py-3 rounded-xl bg-rose-500 text-xs font-bold text-white shadow-glow">Ya, Kosongkan</button>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="pos-confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-white/10 rounded-[2rem] p-6 max-w-sm w-full text-center shadow-2xl">
        <div class="w-16 h-16 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa-solid fa-money-bill-wave"></i></div>
        <h3 class="text-lg font-bold text-white">Konfirmasi Bayar</h3>
        <p class="text-xs text-slate-400 mt-1 mb-4">Pastikan uang sudah diterima.</p>
        <div class="bg-white/5 rounded-2xl p-4 mb-6">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Antrian</p>
            <p id="pos-confirm-antrian" class="text-3xl font-black text-sky-400">-</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="tutupKonfirmasiBayar()" class="py-3 rounded-xl bg-white/5 text-xs font-bold">Batal</button>
            <button id="pos-confirm-ya" class="py-3 rounded-xl bg-emerald-500 text-xs font-bold text-white shadow-glow">Ya, Terima</button>
        </div>
    </div>
</div>

<div id="pos-success-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-white/10 rounded-[2rem] p-6 max-w-sm w-full text-center shadow-2xl">
        <div class="w-16 h-16 rounded-full bg-sky-500/10 text-sky-400 flex items-center justify-center mx-auto mb-4 text-3xl animate-bounce"><i class="fa-solid fa-check-circle"></i></div>
        <h3 class="text-lg font-bold text-white">Pesanan Berhasil</h3>
        <p class="text-4xl font-black text-sky-400 my-4" id="pos-success-antrian">-</p>
        <button onclick="posCloseSuccess()" class="w-full py-3 rounded-xl bg-sky-500 text-xs font-bold text-white">Selesai</button>
    </div>
</div>

<div id="pos-toast-container" class="fixed top-5 right-5 z-[150] flex flex-col gap-3 w-80 pointer-events-none"></div>

<script>
    const STORE_NAME = <?= json_encode($storeNameForPos) ?>;
    let posCart = [];
    let lastId = <?= json_encode($last_id) ?>;
    let csrfHash = '<?= csrf_hash() ?>';
    let csrfName = '<?= csrf_token() ?>';
    let activeTab = 'pos';

    function switchTab(tab) {
        activeTab = tab;
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById('tab-' + tab);
        target.classList.remove('hidden');
        void target.offsetHeight;
        requestAnimationFrame(() => { void target.offsetHeight; window.dispatchEvent(new Event('resize')); });
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-sky-500', 'text-white', 'shadow-glow');
            btn.classList.add('text-slate-400');
        });
        
        const activeBtn = document.getElementById('btn-tab-' + tab);
        activeBtn.classList.remove('text-slate-400');
        activeBtn.classList.add('bg-sky-500', 'text-white', 'shadow-glow');

        if (tab === 'antrean') {
            document.getElementById('header-title').textContent = 'Antrean Pesanan';
            document.getElementById('header-desc').textContent = 'Pantau dan konfirmasi pembayaran antrean.';
            document.getElementById('live-indicator').classList.remove('hidden');
        } else {
            document.getElementById('header-title').textContent = 'POS Pintar';
            document.getElementById('header-desc').textContent = 'Pilih menu dan buat pesanan pelanggan.';
            document.getElementById('live-indicator').classList.add('hidden');
        }
    }

    // Logic POS Pintar
    function posRupiah(n) { return 'Rp ' + parseInt(n||0).toLocaleString('id-ID'); }
    function posEscapeStruk(val) {
        return String(val ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
    
    function getStokById(id) {
        const card = document.querySelector(`.pos-menu-card[data-id="${id}"]`);
        if (!card) return null;
        const v = card.dataset.stok;
        if (v === 'null' || v === '' || v === undefined) return null;
        const n = parseInt(v);
        return isNaN(n) ? null : n;
    }
    function posStokAlert(nama, stok, qty) {
        const cont = document.getElementById('pos-toast-container');
        if (!cont) { alert(`Stok "${nama}" hanya ${stok}. Kamu minta ${qty}.`); return; }
        const el = document.createElement('div');
        el.className = 'pos-toast relative flex items-start gap-3 rounded-2xl border border-rose-400/30 bg-slate-900/95 backdrop-blur p-4 w-full shadow-2xl overflow-hidden';
        el.innerHTML = `
            <div class="w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-400/20 flex items-center justify-center text-rose-300 shrink-0"><i class="fa-solid fa-triangle-exclamation text-sm"></i></div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-extrabold text-white">Stok tidak cukup</p>
                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">"<span class="text-white font-bold">${posEscapeStruk(nama)}</span>" sisa <b class="text-amber-300">${stok}</b>. Minta <b class="text-rose-300">${qty}</b> melebihi stok.</p>
            </div>
            <button type="button" onclick="this.closest('.pos-toast').remove()" class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-xmark text-xs"></i></button>
            <div class="absolute bottom-0 left-0 h-0.5 bg-rose-500 pos-toast-bar"></div>`;
        cont.appendChild(el);
        setTimeout(() => { el.classList.add('closing'); setTimeout(()=> el.remove(), 300); }, 3200);
    }
    function posAddToCart(el) {
        const id = parseInt(el.dataset.id);
        const stok = getStokById(id);
        const nama = el.dataset.nama || '-';
        const ex = posCart.find(i => i.id === id);
        const nextQty = ex ? ex.qty + 1 : 1;
        if (stok !== null && nextQty > stok) {
            posStokAlert(nama, stok, nextQty);
            el.classList.remove('pos-shake'); void el.offsetWidth; el.classList.add('pos-shake');
            setTimeout(()=> el.classList.remove('pos-shake'), 550);
            const cardStok = document.getElementById('pos-stok-text-' + id);
            if (cardStok) { cardStok.classList.add('pos-shake'); setTimeout(()=> cardStok.classList.remove('pos-shake'), 550); }
            return;
        }
        if (ex) ex.qty++;
        else posCart.push({ id, nama, harga: parseInt(el.dataset.harga), qty: 1 });
        posRenderCart();
    }

    function posRenderCart() {
        const cont = document.getElementById('pos-cart-items');
        const empty = document.getElementById('pos-cart-empty');
        const btn = document.getElementById('pos-btn-submit');
        if (posCart.length === 0) {
            cont.innerHTML = ''; empty.classList.remove('hidden'); btn.disabled = true;
            updateBadges(); updateTotals(); return;
        }
        empty.classList.add('hidden'); btn.disabled = false;
        cont.innerHTML = posCart.map((i, idx) => `
            <div class="flex items-center gap-3 bg-white/5 border border-white/5 p-3 rounded-xl">
                <div class="flex-1 min-w-0"><p class="text-[11px] font-bold truncate">${i.nama}</p><p class="text-[10px] text-sky-300">@ ${posRupiah(i.harga)}</p></div>
                <div class="flex items-center gap-2">
                    <button onclick="posUpdateQty(${idx},-1)" class="w-6 h-6 rounded bg-rose-500/20 text-rose-400 text-[10px]">-</button>
                    <span class="text-xs font-bold">${i.qty}</span>
                    <button onclick="posUpdateQty(${idx},1)" class="w-6 h-6 rounded bg-sky-500/20 text-sky-400 text-[10px]">+</button>
                </div>
            </div>
        `).join('');
        updateBadges(); updateTotals();
    }

    function posUpdateQty(idx, d) {
        const item = posCart[idx];
        if (!item) return;
        if (d > 0) {
            const stok = getStokById(item.id);
            const next = item.qty + d;
            if (stok !== null && next > stok) {
                posStokAlert(item.nama, stok, next);
                const row = document.getElementById('pos-cart-items')?.children[idx];
                if (row) { row.classList.remove('pos-shake'); void row.offsetWidth; row.classList.add('pos-shake','pos-stok-exceed'); setTimeout(()=> row.classList.remove('pos-shake','pos-stok-exceed'), 600); }
                return;
            }
        }
        item.qty += d;
        if (item.qty <= 0) posCart.splice(idx, 1);
        posRenderCart();
    }

    function updateBadges() {
        document.querySelectorAll('.pos-qty-badge').forEach(b => b.classList.add('hidden'));
        posCart.forEach(i => {
            const b = document.getElementById('pos-qty-'+i.id);
            if(b) { b.textContent = i.qty; b.classList.remove('hidden'); b.classList.add('flex'); }
        });
    }

    function updateTotals() {
        let total = 0; posCart.forEach(i => total += (i.harga * i.qty));
        document.getElementById('pos-total').textContent = posRupiah(total);
        posHitungKembalian();
    }

    function posHitungKembalian() {
        const paymentMethod = document.querySelector('input[name="pos_payment_method"]:checked')?.value || 'tunai';
        const section = document.getElementById('pos-uang-bayar-section');
        const kembalianDisplay = document.getElementById('pos-kembalian-display');
        const kurangDisplay = document.getElementById('pos-kurang-display');

        if (paymentMethod !== 'tunai') {
            section.classList.add('hidden');
            if (kembalianDisplay) kembalianDisplay.classList.add('hidden');
            if (kurangDisplay) kurangDisplay.classList.add('hidden');
            return;
        }

        section.classList.remove('hidden');

        let total = 0;
        posCart.forEach(i => { total += (i.harga * i.qty); });

        const uangBayar = parseInt(document.getElementById('pos-uang-bayar')?.value || 0);

        if (uangBayar <= 0) {
            if (kembalianDisplay) kembalianDisplay.classList.add('hidden');
            if (kurangDisplay) kurangDisplay.classList.add('hidden');
            return;
        }

        if (uangBayar >= total) {
            const kembalian = uangBayar - total;
            document.getElementById('pos-kembalian').textContent = posRupiah(kembalian);
            if (kembalianDisplay) kembalianDisplay.classList.remove('hidden');
            if (kurangDisplay) kurangDisplay.classList.add('hidden');
        } else {
            const kurang = total - uangBayar;
            document.getElementById('pos-kurang').textContent = posRupiah(kurang);
            if (kembalianDisplay) kembalianDisplay.classList.add('hidden');
            if (kurangDisplay) kurangDisplay.classList.remove('hidden');
        }
    }

    function posRefreshStok() {
        fetch('<?= base_url('kasir/pos/stok') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(r => r.json()).then(j => {
                if (!j.success || !Array.isArray(j.data)) return;
                j.data.forEach(m => {
                    const card = document.querySelector(`.pos-menu-card[data-id="${m.id}"]`);
                    if (!card) return;
                    const stokRaw = m.stok;
                    const isActive = parseInt(m.is_active ?? 1);
                    const isHabis = (stokRaw !== null && parseInt(stokRaw) <= 0) || !isActive;
                    const isLow = stokRaw !== null && parseInt(stokRaw) > 0 && parseInt(stokRaw) <= 10;
                    card.dataset.stok = stokRaw === null ? 'null' : String(stokRaw);
                    const wrap = document.getElementById('pos-stok-badge-wrap-' + m.id);
                    if (wrap) {
                        if (isHabis) wrap.innerHTML = '<span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-1 rounded-full bg-rose-500 text-white shadow"><i class="fa-solid fa-xmark text-[8px]"></i> Habis</span>';
                        else if (isLow) wrap.innerHTML = '<span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-1 rounded-full bg-amber-500 text-white shadow animate-pulse"><i class="fa-solid fa-triangle-exclamation text-[8px]"></i> Stok menipis \u00b7 ' + stokRaw + '</span>';
                        else wrap.innerHTML = '';
                    }
                    const text = document.getElementById('pos-stok-text-' + m.id);
                    const bar = document.getElementById('pos-stok-bar-' + m.id);
                    if (text) {
                        if (stokRaw === null) { text.classList.add('hidden'); text.innerHTML = ''; }
                        else if (isHabis) { text.className = 'text-[10px] font-bold mt-1 flex items-center gap-1 text-rose-400'; text.innerHTML = '<i class="fa-solid fa-circle-xmark text-[9px]"></i> Stok Habis'; text.classList.remove('hidden'); }
                        else if (isLow) { text.className = 'text-[10px] font-bold mt-1 flex items-center gap-1 text-amber-300'; text.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Sisa stok ' + stokRaw; text.classList.remove('hidden'); }
                        else { text.className = 'text-[10px] font-bold mt-1 flex items-center gap-1 text-slate-500'; text.textContent = 'Stok ' + stokRaw; text.classList.remove('hidden'); }
                    }
                    if (bar) { if (isLow) bar.classList.remove('hidden'); else bar.classList.add('hidden'); }
                    card.classList.remove('opacity-50','pointer-events-none','border-white/10','bg-white/[.04]','border-amber-400/30','bg-amber-500/[.06]');
                    if (isHabis) card.classList.add('opacity-50','pointer-events-none','border-white/10','bg-white/[.04]');
                    else if (isLow) card.classList.add('border-amber-400/30','bg-amber-500/[.06]');
                    else card.classList.add('border-white/10','bg-white/[.04]');
                });
            }).catch(()=>{});
    }

    async function posSubmitOrder() {
        const nama = document.getElementById('pos-nama').value.trim();
        const catatan = document.getElementById('pos-catatan').value.trim();
        if (!nama) { posAlert('Perhatian', 'Nama pelanggan wajib diisi'); return; }
        if (posCart.length === 0) { posAlert('Perhatian', 'Keranjang masih kosong'); return; }
        const paymentMethod = document.querySelector('input[name="pos_payment_method"]:checked').value;
        if (paymentMethod === 'tunai') {
            let total = 0; posCart.forEach(i => total += (i.harga * i.qty));
            const ub = parseInt(document.getElementById('pos-uang-bayar').value || 0);
            if (!ub || ub < total) { posAlert('Pembayaran', 'Uang bayar tidak cukup'); return; }
        }
        const btn = document.getElementById('pos-btn-submit');
        btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        const fd = new FormData();
        fd.append(csrfName, csrfHash);
        fd.append('nama_pemesan', nama);
        fd.append('catatan', catatan);
        fd.append('payment_method', paymentMethod);
        fd.append('uang_bayar', document.getElementById('pos-uang-bayar').value);
        fd.append('items', JSON.stringify(posCart.map(i => ({id:i.id, qty:i.qty}))));

        try {
            const res = await fetch('<?= base_url('kasir/pos/submit') ?>', { method: 'POST', body: fd });
            const d = await res.json();
            if (d.success) {
                posCart = []; posRenderCart(); document.getElementById('pos-nama').value = '';
                document.getElementById('pos-catatan').value = '';
                const ub = document.getElementById('pos-uang-bayar'); if (ub) ub.value = '';
                document.getElementById('pos-success-antrian').textContent = d.nomor_antrian;
                document.getElementById('pos-success-modal').classList.remove('hidden');
                document.getElementById('pos-success-modal').classList.add('flex');

                tambahAntreanDOM(d.receipt_data);
                posRefreshStok();
                posAlert('Berhasil', d.message, 'emerald');

                if (d.receipt_data) {
                    // posCetakStruk(d.receipt_data); -> Dihapus dari buat pesanan
                }
            } else { posAlert('Gagal', d.message || 'Gagal'); }
        } catch(e) { posAlert('Error', 'Error koneksi'); }
        btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-2"></i>Buat Pesanan';
    }

    function posCloseSuccess() { document.getElementById('pos-success-modal').classList.add('hidden'); }

    // Logic Antrean
    let bayarFormPending = null;
    let batalKodePending = '';
    function konfirmasiBayar(e, form) {
        e.preventDefault(); bayarFormPending = form;
        document.getElementById('pos-confirm-antrian').textContent = form.dataset.bayarAntrian;
        const m = document.getElementById('pos-confirm-modal');
        m.classList.remove('hidden'); m.classList.add('flex');
        return false;
    }
    function tutupKonfirmasiBayar() { document.getElementById('pos-confirm-modal').classList.add('hidden'); }
    function konfirmasiBatal(kode, antri) {
        batalKodePending = kode;
        document.getElementById('pos-batal-antrian').textContent = antri;
        const m = document.getElementById('pos-batal-modal');
        m.classList.remove('hidden'); m.classList.add('flex');
    }
    function tutupKonfirmasiBatal() {
        document.getElementById('pos-batal-modal').classList.add('hidden');
        document.getElementById('pos-batal-modal').classList.remove('flex');
        batalKodePending = '';
    }
    function posClearCart() {
        if (posCart.length === 0) return;
        const totalQty = posCart.reduce((s, i) => s + (parseInt(i.qty) || 0), 0);
        document.getElementById('pos-clear-count').textContent = totalQty;
        const m = document.getElementById('pos-clear-modal');
        m.classList.remove('hidden'); m.classList.add('flex');
    }
    function tutupKonfirmasiClear() {
        const m = document.getElementById('pos-clear-modal');
        m.classList.add('hidden'); m.classList.remove('flex');
    }
    document.getElementById('pos-clear-ya').onclick = function() {
        tutupKonfirmasiClear();
        posCart = [];
        posRenderCart();
        const ub = document.getElementById('pos-uang-bayar');
        if (ub) ub.value = '';
        posHitungKembalian();
    };
    document.getElementById('pos-batal-ya').onclick = async function() {
        if (!batalKodePending) return;
        const kode = batalKodePending;
        tutupKonfirmasiBatal();
        try {
            const fd = new FormData(); fd.append(csrfName, csrfHash);
            const res = await fetch('<?= base_url('kasir/batal/') ?>' + encodeURIComponent(kode), { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const d = await res.json();
            if (d.success) {
                const card = document.querySelector(`[data-order-card="${kode}"]`);
                if (card) card.remove();
                const count = document.getElementById('count-pending');
                count.textContent = Math.max(0, parseInt(count.textContent || '0') - 1);
                document.getElementById('badge-pending-count').textContent = count.textContent;
                if (parseInt(count.textContent || '0') === 0) document.getElementById('empty-state').classList.remove('hidden');
                posRefreshStok();
            } else { alert(d.message || 'Gagal membatalkan'); }
        } catch(e) { alert('Gagal terhubung'); }
    };
    
    document.getElementById('pos-confirm-ya').onclick = async function() {
        const form = bayarFormPending; tutupKonfirmasiBayar();
        const fd = new FormData(form); fd.append(csrfName, csrfHash);
        try {
                const res = await fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                const d = await res.json();
                if (d.success) {
                    const card = document.querySelector(`[data-order-card="${d.data.kode_pesanan}"]`);
                    if (card) card.remove();
                    document.getElementById('count-pending').textContent = Math.max(0, parseInt(document.getElementById('count-pending').textContent)-1);
                    document.getElementById('badge-pending-count').textContent = document.getElementById('count-pending').textContent;
                    document.getElementById('pendapatan-hari-ini').textContent = posRupiah(parseInt(document.getElementById('pendapatan-hari-ini').dataset.amount) + parseInt(d.data.total_harga));
                    document.getElementById('pendapatan-hari-ini').dataset.amount = parseInt(document.getElementById('pendapatan-hari-ini').dataset.amount) + parseInt(d.data.total_harga);
                    
                    if (d.data) posCetakStruk(d.data);
                    
                    posRefreshStok();
                }
        } catch(e) {}
    };

    function posCetakStruk(data) {
        const items = Array.isArray(data.items) ? data.items : [];
        let itemsHTML = '';
        let subtotal = 0;
        items.forEach(item => {
            const harga = parseInt(item.harga ?? 0);
            const qty = parseInt(item.qty ?? 0);
            const sub = harga * qty;
            subtotal += sub;
            itemsHTML += `
                <div class="item">
                    <div>
                        <strong>${posEscapeStruk(item.nama_menu ?? '-')}</strong>
                        <small>${posEscapeStruk(qty)} x Rp ${posRupiah(harga).replace('Rp ','')}</small>
                    </div>
                    <span>Rp ${posRupiah(sub).replace('Rp ','')}</span>
                </div>`;
        });
        const total = parseInt(data.total_harga ?? subtotal);
        const catatanHTML = data.catatan ? `
            <div class="note"><strong>Catatan:</strong><br>${posEscapeStruk(data.catatan)}</div>` : '';
        const kasirLabel = posEscapeStruk(data.kasir_nama ?? data.kasir ?? '-') || '-';
        const cashHTML = String(data.metode_pembayaran ?? '').toLowerCase() === 'tunai' && data.uang_bayar != null && data.uang_bayar !== '' ? `
            <div class="row"><span class="label">Bayar</span><span class="value">Rp ${(parseInt(data.uang_bayar)||0).toLocaleString('id-ID')}</span></div>
            <div class="row total-row"><span>Kembalian</span><span>Rp ${(parseInt(data.kembalian)||0).toLocaleString('id-ID')}</span></div>
            <div class="line"></div>` : '';
        const html = `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Struk ${posEscapeStruk(data.kode_pesanan)}</title>
                <style>
                    *{box-sizing:border-box;}
                    body{margin:0;padding:18px;color:#111;background:#fff;font-family:Arial,sans-serif;font-size:12px;}
                    .receipt{width:100%;max-width:320px;margin:0 auto;}
                    .center{text-align:center;}
                    .store{font-size:20px;font-weight:900;margin-bottom:3px;}
                    .subtitle{color:#555;font-size:10px;}
                    .queue{margin:15px 0;padding:12px;text-align:center;border:2px dashed #111;}
                    .queue span{display:block;font-size:10px;font-weight:700;text-transform:uppercase;}
                    .queue strong{display:block;margin-top:4px;font-size:34px;font-weight:900;}
                    .line{margin:12px 0;border-top:1px dashed #777;}
                    .row{display:flex;justify-content:space-between;gap:12px;margin-bottom:5px;}
                    .label{color:#555;}
                    .value{text-align:right;font-weight:700;}
                    .item{display:flex;justify-content:space-between;gap:12px;margin-bottom:9px;}
                    .item strong{display:block;font-size:11px;}
                    .item small{display:block;margin-top:2px;color:#666;font-size:9px;}
                    .note{margin-top:10px;padding:8px;border:1px dashed #777;font-size:10px;}
                    .total-row{font-size:15px;font-weight:900;}
                    .thanks{margin-top:15px;text-align:center;color:#555;font-size:10px;line-height:1.5;}
                    @media print{body{padding:0;}@page{margin:5mm;}}
                </style>
            </head>
            <body>
                <div class="receipt">
                    <div class="center">
                        <div class="store">${posEscapeStruk(STORE_NAME)}</div>
                        <div class="subtitle">Struk Pesanan</div>
                    </div>
                    <div class="queue">
                        <span>Nomor Antrian</span>
                        <strong>${posEscapeStruk(data.nomor_antrian ?? '-')}</strong>
                    </div>
                    <div class="line"></div>
                    <div class="row"><span class="label">Kode</span><span class="value">${posEscapeStruk(data.kode_pesanan)}</span></div>
                    <div class="row"><span class="label">Nama</span><span class="value">${posEscapeStruk(data.nama_pemesan ?? '-')}</span></div>
                    <div class="row"><span class="label">Metode</span><span class="value">${posEscapeStruk(data.metode_label ?? data.metode_pembayaran ?? '-')}</span></div>
                    <div class="row"><span class="label">Waktu</span><span class="value">${posEscapeStruk(data.waktu ?? '-')}</span></div>
                    <div class="row"><span class="label">Dilayani</span><span class="value">${kasirLabel}</span></div>
                    <div class="line"></div>
                    ${itemsHTML}
                    ${catatanHTML}
                    <div class="line"></div>
                    <div class="row total-row"><span>Total</span><span>Rp ${total.toLocaleString('id-ID')}</span></div>
                    <div class="line"></div>
                    ${cashHTML}
                    <div class="thanks">Terima kasih.<br>Simpan struk ini sebagai bukti transaksi.</div>
                </div>
                <script>window.onload=function(){setTimeout(function(){window.print();setTimeout(function(){window.close();},500);},250);};<\/script>
            </body>
            </html>`;
        const w = window.open('','_blank','width=380,height=650');
        if (!w) { alert('Popup diblokir browser. Izinkan popup untuk cetak.'); return; }
        w.document.open(); w.document.write(html); w.document.close();
    }

    document.addEventListener('DOMContentLoaded', () => posHitungKembalian());

    function escapeHTMLAntrean(v) {
        return String(v ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;");
    }
    function renderAntreanCard(p) {
        const kode = escapeHTMLAntrean(p.kode_pesanan);
        const antri = escapeHTMLAntrean(p.nomor_antrian ?? '-');
        const nama = escapeHTMLAntrean(p.nama_pemesan ?? '-');
        const metodeLabel = escapeHTMLAntrean(p.metode_label ?? p.metode_pembayaran ?? '-');
        const total = posRupiah(p.total_harga ?? 0);
        const waktu = escapeHTMLAntrean(p.waktu ?? (p.created_at ? new Date(p.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) : '-'));
        const csrfField = `<?= csrf_field() ?>`;
        let itemsHTML = '';
        if (Array.isArray(p.items) && p.items.length) {
            itemsHTML = p.items.map(it => {
                const nm = escapeHTMLAntrean(it.nama_menu ?? '-');
                const qty = parseInt(it.qty ?? 0);
                const sub = posRupiah((parseInt(it.harga ?? 0) * qty));
                return `<li class="flex justify-between"><span>${nm} <b class="text-sky-400 ml-1">x${qty}</b></span><span>${sub}</span></li>`;
            }).join('');
        } else {
            itemsHTML = `<li class="text-slate-500 text-[11px]">Menunggu detail item...</li>`;
        }
        return `
            <div class="rounded-[1.5rem] border border-amber-400/20 bg-amber-500/[.03] overflow-hidden shadow-soft" data-order-card="${kode}">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-5 py-4 border-b border-white/10 bg-white/[.02]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-purple-600 flex items-center justify-center font-black text-white shadow-glow shrink-0">${antri}</div>
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-white truncate">${nama} <span class="text-[10px] text-slate-500 ml-2">#${kode}</span></p>
                            <p class="text-[10px] text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i>${waktu} · ${metodeLabel}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-sm font-black text-white whitespace-nowrap">${total}</span>
                        <button type="button" onclick="konfirmasiBatal('${kode}','${antri}')" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-rose-500/20 border border-white/10 hover:border-rose-400/30 flex items-center justify-center text-slate-400 hover:text-rose-300 transition" title="Batalkan pesanan"><i class="fa-solid fa-trash-can text-xs"></i></button>
                        <form action="<?= base_url('kasir/telah-bayar/') ?>${kode}" method="post" data-bayar-antrian="${antri}" data-bayar-nama="${nama}" onsubmit="return konfirmasiBayar(event, this)">
                            ${csrfField}
                            <button type="submit" class="rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition shadow-glow whitespace-nowrap">Telah Bayar</button>
                        </form>
                    </div>
                </div>
                <div class="px-5 py-3 bg-transparent">
                    <ul class="text-xs space-y-1 text-slate-400">${itemsHTML}</ul>
                </div>
            </div>`;
    }
    function tambahAntreanDOM(p) {
        if(document.querySelector(`[data-order-card="${p.kode_pesanan}"]`)) return;
        document.getElementById('order-container').insertAdjacentHTML('afterbegin', renderAntreanCard(p));
        if (p.kode_pesanan && String(p.kode_pesanan) > String(lastId)) lastId = p.kode_pesanan;
        const count = document.getElementById('count-pending');
        count.textContent = parseInt(count.textContent || '0') + 1;
        document.getElementById('badge-pending-count').textContent = count.textContent;
        document.getElementById('empty-state').classList.add('hidden');
    }

    // Auto Refresh Antrean
    setInterval(async () => {
        if(activeTab !== 'antrean') return;
        try {
            const res = await fetch('<?= base_url('kasir/cek_baru') ?>/' + encodeURIComponent(lastId) + '?t=' + Date.now(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const d = await res.json();
            if(d.ada && Array.isArray(d.data)) {
                lastId = d.last_id ?? lastId;
                d.data.forEach(p => {
                    if(!document.querySelector(`[data-order-card="${p.kode_pesanan}"]`)) {
                        document.getElementById('order-container').insertAdjacentHTML('afterbegin', renderAntreanCard(p));
                        const count = document.getElementById('count-pending');
                        count.textContent = parseInt(count.textContent || '0') + 1;
                        document.getElementById('badge-pending-count').textContent = count.textContent;
                    }
                });
                document.getElementById('empty-state').classList.add('hidden');
            }
        } catch(e) {}
    }, 3000);
</script>

<style>
    .shadow-glow { box-shadow: 0 0 15px rgba(14, 165, 233, 0.3); }
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .pos-toast { pointer-events: auto; animation: posToastIn .38s cubic-bezier(.21,1.02,.73,1) both; }
    .pos-toast.closing { animation: posToastOut .3s ease forwards; }
    @keyframes posToastIn { from { opacity:0; transform: translateX(28px) scale(.95); } to { opacity:1; transform: translateX(0) scale(1); } }
    @keyframes posToastOut { from { opacity:1; transform: translateX(0) scale(1); } to { opacity:0; transform: translateX(28px) scale(.95); } }
    .pos-toast-bar { animation: posToastBar 3.2s linear forwards; }
    @keyframes posToastBar { from { width:100%; } to { width:0%; } }
    @keyframes posShake { 0%,100%{ transform: translateX(0);} 20%{ transform: translateX(-4px);} 40%{ transform: translateX(4px);} 60%{ transform: translateX(-3px);} 80%{ transform: translateX(3px);} }
    .pos-shake { animation: posShake .45s ease; }
    .pos-stok-exceed { border-color: rgba(244,63,94,.6) !important; background: rgba(244,63,94,.08) !important; }
</style>

<?= $this->endSection() ?>