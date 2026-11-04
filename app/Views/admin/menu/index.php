<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-10">

    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-400/20 text-amber-300 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-bowl-food"></i>
                        Menu Restoran
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-white">
                        Kelola Menu
                    </h1>

                    <p class="text-sm lg:text-base text-slate-400 mt-2 max-w-2xl">
                        Daftar semua item menu yang tersedia untuk pelanggan.
                    </p>
                </div>

                <button onclick="openModalMenu()"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Menu
                </button>
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

    <!-- TAB 1: DATA MENU -->
    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden" id="section-menu">
        <div class="p-5 lg:p-6 border-b border-white/10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-white">Data Menu</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Atur menu, harga, dan status aktif.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-white/[.04] border border-white/10 px-4 py-2">
                    <i class="fa-solid fa-database text-slate-500 text-xs"></i>
                    <span class="text-xs font-semibold text-slate-400">
                        <?= number_format(count($menus ?? []), 0, ',', '.') ?> Data
                    </span>
                </div>
                <button onclick="switchTab('stok')" class="flex items-center gap-2 rounded-2xl bg-sky-500 hover:bg-sky-600 px-4 py-2 text-xs font-bold text-white transition shadow-glow">
                    <i class="fa-solid fa-box"></i> Pengisian Stok
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 bg-white/[.025] text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                        <th class="px-5 py-4 whitespace-nowrap">No</th>
                        <th class="px-5 py-4 whitespace-nowrap">Menu</th>
                        <th class="px-5 py-4 whitespace-nowrap">Harga</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Stok Total</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Status</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    <?php if (! empty($menus)): ?>
                        <?php foreach ($menus as $m): ?>
                            <tr class="hover:bg-white/[.025] transition" data-menu-id="<?= esc($m['id']) ?>" data-nama-menu="<?= esc($m['nama_menu'] ?? '') ?>" data-harga="<?= esc($m['harga'] ?? '') ?>" data-is-active="<?= esc($m['is_active'] ?? 1) ?>" data-gambar="<?= esc($m['gambar'] ?? '') ?>">
                                <td class="px-5 py-4 text-slate-500 whitespace-nowrap"><?= esc($m['id'] ?? '-') ?></td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <?php if (! empty($m['gambar'])): ?>
                                            <img src="<?= base_url('uploads/menu/' . $m['gambar']) ?>" alt="<?= esc($m['nama_menu'] ?? 'Menu') ?>" class="w-12 h-12 rounded-2xl object-cover border border-white/10">
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-2xl bg-white/[.04] border border-white/10 flex items-center justify-center"><i class="fa-solid fa-image text-slate-500"></i></div>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-white truncate max-w-[220px]"><?= esc($m['nama_menu'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm font-extrabold text-white whitespace-nowrap">
                                    Rp <?= number_format($m['harga'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider bg-sky-500/10 text-sky-300 border-sky-400/20">
                                        <?= $m['total_stok'] ?? 0 ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <form action="<?= base_url('admin/menu/toggle/' . $m['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider transition <?= ! empty($m['is_active']) ? 'bg-emerald-500/10 text-emerald-300 border-emerald-400/20 hover:bg-emerald-500/15' : 'bg-slate-500/10 text-slate-400 border-slate-400/20 hover:bg-slate-500/15' ?>">
                                            <i class="fa-solid fa-circle text-[7px] <?= ! empty($m['is_active']) ? 'text-emerald-400' : 'text-slate-500' ?>"></i>
                                            <?= ! empty($m['is_active']) ? 'Aktif' : 'Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openModalMenu(<?= $m['id'] ?>)" class="inline-flex items-center gap-2 text-xs font-bold text-sky-300 hover:text-white bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/20 px-3 py-2 rounded-xl transition"><i class="fa-solid fa-pen"></i> Edit</button>
                                        <button type="button" onclick="confirmDeleteMenu('<?= base_url('admin/menu/delete/' . $m['id']) ?>', '<?= esc($m['nama_menu'] ?? '-', 'js') ?>')" class="inline-flex items-center gap-2 text-xs font-bold text-rose-300 hover:text-white bg-rose-500/10 hover:bg-rose-500/20 border border-rose-400/20 px-3 py-2 rounded-xl transition"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-5 py-20 text-center">
                                <p class="text-sm font-bold text-white">Belum ada menu</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: PENGISIAN STOK MENU -->
    <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] backdrop-blur shadow-soft overflow-hidden hidden" id="section-stok">
        <div class="p-5 lg:p-6 border-b border-white/10 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-white">Pengisian Stok Menu</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Isi stok per menu. Stok akan ditambahkan ke total stok menu yang dipilih.
                </p>
            </div>

            <button onclick="switchTab('menu')" class="flex items-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-4 py-2 text-xs font-bold text-slate-300 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Menu
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 bg-white/[.025] text-xs font-bold text-slate-500 uppercase tracking-[0.16em]">
                        <th class="px-5 py-4 whitespace-nowrap">No</th>
                        <th class="px-5 py-4 whitespace-nowrap">Menu</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Stok Saat Ini</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Jumlah</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Tanggal</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    <?php if (! empty($menus)): ?>
                        <?php $no = 1;
                        foreach ($menus as $m): ?>
                            <tr class="hover:bg-white/[.025] transition-colors group">
                                <td class="px-5 py-4 text-slate-500 font-mono text-sm"><?= esc($no++) ?></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-white/[.04] border border-white/10 flex items-center justify-center">
                                            <?php if (! empty($m['gambar'])): ?>
                                                <img src="<?= base_url('uploads/menu/' . $m['gambar']) ?>" alt="<?= esc($m['nama_menu']) ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fa-solid fa-image text-slate-600"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-white"><?= esc($m['nama_menu']) ?></span>
                                            <span class="text-[10px] text-slate-500 uppercase tracking-widest">ID: <?= esc($m['id']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-400/20 text-sky-300 font-bold text-xs">
                                        <?= $m['total_stok'] ?? 0 ?>
                                    </div>
                                </td>
                                <form action="<?= base_url('admin/riwayat-stok/store') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="menu_id" value="<?= esc($m['id']) ?>">
                                    <td class="px-5 py-4 text-center">
                                        <input type="number" name="stok" min="1"
                                            class="w-28 rounded-2xl bg-slate-950 border border-white/10 px-4 py-2.5 text-sm text-white text-center focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all placeholder-slate-700"
                                            placeholder="Jumlah..." required>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>"
                                            class="w-40 rounded-2xl bg-slate-950 border border-white/10 px-4 py-2.5 text-sm text-white text-center focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all" required>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-900/20 transition-all active:scale-95">
                                            <i class="fa-solid fa-check"></i> Simpan
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-5 py-20 text-center">
                                <p class="text-sm font-bold text-white">Belum ada menu</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalMenu" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur hidden" onclick="if(event.target === this) closeModalMenu()">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-900 via-slate-950 to-black shadow-2xl">
        <div class="sticky top-0 p-5 lg:p-6 border-b border-white/10 bg-slate-950/95 flex items-center justify-between">
            <h2 class="text-lg font-extrabold text-white">
                <span id="modalTitle">Tambah Menu</span>
            </h2>
            <button onclick="closeModalMenu()" class="w-10 h-10 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 text-slate-300 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="menuForm" action="<?= base_url('admin/menu/store') ?>" method="post" enctype="multipart/form-data" class="p-5 lg:p-6 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" id="menuId" name="menu_id" value="">

            <div id="errorAlert" class="hidden rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                <p class="font-bold mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Terdapat kesalahan:
                </p>
                <ul id="errorList" class="list-disc list-inside space-y-1 text-xs"></ul>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                            Nama Menu <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="nama_menu" id="namaMenu"
                            placeholder="Contoh: Es Teh Manis, Nasi Goreng"
                            class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                            Harga <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-500 font-semibold">Rp</span>
                            <input type="number" name="harga" id="harga" min="0" placeholder="15000"
                                class="w-full rounded-2xl bg-slate-950/60 border border-white/10 pl-11 pr-4 py-3 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                            Status <span class="text-rose-400">*</span>
                        </label>
                        <select name="is_active" id="isActive"
                            class="w-full rounded-2xl bg-slate-950/60 border border-white/10 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400" required>
                            <option class="bg-slate-950 text-white" value="1">Aktif</option>
                            <option class="bg-slate-950 text-white" value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[.03] p-4 flex flex-col">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-[0.16em] mb-2">
                        Gambar Menu
                    </label>
                    <div class="aspect-square rounded-2xl bg-slate-950/60 border border-white/10 flex items-center justify-center overflow-hidden mb-3 flex-1">
                        <div id="gambarPreview" class="text-center w-full h-full flex items-center justify-center">
                            <div>
                                <div class="w-12 h-12 rounded-2xl bg-white/[.04] border border-white/10 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-image text-slate-500 text-xl"></i>
                                </div>
                                <p class="text-xs text-slate-500">Belum ada gambar</p>
                            </div>
                        </div>
                    </div>

                    <input type="file" name="gambar" id="gambar" accept="image/jpg,image/jpeg,image/png,image/webp"
                        class="block w-full text-xs text-slate-400 file:mr-3 file:rounded-xl file:border-0 file:bg-sky-500/15 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-sky-300 hover:file:bg-sky-500/25 mb-2">
                    <p class="text-xs text-slate-500">JPG, PNG, WebP. Max 2MB</p>
                </div>
            </div>

            <div class="flex gap-3 pt-5 border-t border-white/10">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3 text-sm font-bold text-white shadow-glow hover:opacity-95 transition">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span id="submitBtn">Tambah Menu</span>
                </button>
                <button type="button" onclick="closeModalMenu()" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 px-5 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Menu -->
<div id="deleteMenuModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeDeleteMenuModal()"></div>

    <div class="relative w-full max-w-sm rounded-[2rem] border border-white/10 bg-slate-900/95 p-6 backdrop-blur shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl animate-pulse"></i>
        </div>

        <h3 class="text-lg font-extrabold text-white mb-2">Hapus Menu?</h3>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Menu <span id="deleteMenuName" class="text-white font-bold"></span> akan dihapus secara permanen beserta gambar dan data terkait.
        </p>

        <form id="deleteMenuForm" action="" method="post" class="flex items-center gap-3">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeleteMenuModal()" class="flex-1 rounded-2xl bg-white/[.04] hover:bg-white/[.08] border border-white/10 py-3 text-xs font-bold text-slate-300 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 rounded-2xl bg-rose-600 hover:bg-rose-700 py-3 text-xs font-bold text-white shadow-glow transition">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.getElementById('section-menu').classList.toggle('hidden', tab !== 'menu');
        document.getElementById('section-stok').classList.toggle('hidden', tab !== 'stok');
    }

    function confirmDeleteMenu(actionUrl, menuName) {
        document.getElementById('deleteMenuForm').action = actionUrl;
        document.getElementById('deleteMenuName').textContent = menuName;

        const modal = document.getElementById('deleteMenuModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteMenuModal() {
        const modal = document.getElementById('deleteMenuModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openModalMenu(menuId = null) {
        const modal = document.getElementById('modalMenu');
        const form = document.getElementById('menuForm');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const input = document.getElementById('menuId');

        if (menuId) {
            title.textContent = 'Edit Menu';
            submitBtn.textContent = 'Simpan Perubahan';
            form.action = `<?= base_url('admin/menu/update') ?>/${menuId}`;
            input.value = menuId;

            const row = document.querySelector(`[data-menu-id="${menuId}"]`);
            if (row) {
                document.getElementById('namaMenu').value = row.dataset.namaMenu || '';
                document.getElementById('harga').value = row.dataset.harga || '';
                document.getElementById('isActive').value = row.dataset.isActive || '1';

                if (row.dataset.gambar) {
                    document.getElementById('gambarPreview').innerHTML = `<img src="<?= base_url('uploads/menu/') ?>${row.dataset.gambar}" class="w-full h-full object-cover" alt="">`;
                }
            }
        } else {
            title.textContent = 'Tambah Menu';
            submitBtn.textContent = 'Tambah Menu';
            form.action = `<?= base_url('admin/menu/store') ?>`;
            form.reset();
            input.value = '';
            document.getElementById('gambarPreview').innerHTML = `<div><div class="w-12 h-12 rounded-2xl bg-white/[.04] border border-white/10 flex items-center justify-center mx-auto mb-2"><i class="fa-solid fa-image text-slate-500 text-xl"></i></div><p class="text-xs text-slate-500">Belum ada gambar</p></div>`;
        }

        document.getElementById('errorAlert').classList.add('hidden');
        modal.classList.remove('hidden');
    }

    function closeModalMenu() {
        document.getElementById('modalMenu').classList.add('hidden');
        document.getElementById('menuForm').reset();
    }

    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('gambarPreview').innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover" alt="">`;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('menuForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            if (response.redirected) {
                window.location.href = response.url;
            } else {
                const data = await response.json();
                if (data.errors) {
                    const errorList = document.getElementById('errorList');
                    errorList.innerHTML = '';
                    for (const field in data.errors) {
                        const li = document.createElement('li');
                        li.textContent = data.errors[field];
                        errorList.appendChild(li);
                    }
                    document.getElementById('errorAlert').classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>

<?= $this->endSection() ?>