<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-extrabold text-white leading-tight">Kasir</h1>
            <p class="text-xs text-slate-500">POS Pintar & Konfirmasi dalam satu halaman — pindah pakai switch.</p>
        </div>
        <div class="inline-flex self-start sm:self-auto p-1 rounded-[1.4rem] bg-white/[.06] border border-white/10 backdrop-blur gap-1">
            <button id="btn-tab-pos" onclick="switchKasirTab('pos')" class="px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 bg-white text-slate-900 shadow"><i class="fa-solid fa-cash-register"></i> POS Pintar</button>
            <button id="btn-tab-konfirmasi" onclick="switchKasirTab('konfirmasi')" class="px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 text-slate-400 hover:text-white"><i class="fa-solid fa-bell-concierge"></i> Konfirmasi <span id="badge-menunggu" class="min-w-5 h-5 px-1.5 rounded-full bg-amber-500 text-white text-[10px] font-black flex items-center justify-center">3</span></button>
        </div>
    </div>

    <div id="panel-pos">
        <div class="flex flex-col lg:flex-row gap-4 min-h-[540px]">
            <div class="flex-1 min-w-0 flex flex-col">
                <div class="rounded-[2rem] border border-white/10 bg-white/[.035] p-4 mb-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div><div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/10 border border-sky-400/20 text-sky-300 text-xs font-semibold mb-2"><i class="fa-solid fa-cash-register"></i> POS Pintar — Dummy</div><h2 class="text-xl font-extrabold text-white">Pilih Menu</h2><p class="text-xs text-slate-500">Data dummy, tambah ke keranjang jalan via JS lokal.</p></div>
                        <div class="relative w-full sm:w-72"><i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i><input id="pos-search" placeholder="Cari menu..." class="w-full bg-white/[.04] border border-white/10 text-white rounded-2xl pl-11 pr-4 py-3 text-sm outline-none focus:border-sky-400/30"></div>
                    </div>
                </div>
                <div id="pos-grid" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3"></div>
            </div>
            <div class="w-full lg:w-[390px] flex flex-col rounded-[2rem] border border-white/10 bg-white/[.035] overflow-hidden">
                <div class="p-4 border-b border-white/10 flex items-center justify-between"><h2 class="font-extrabold text-white text-sm">Keranjang <span id="pos-count" class="text-slate-400 font-semibold">0 item</span></h2><button onclick="clearCart()" class="text-xs text-slate-400 hover:text-rose-300">Kosongkan</button></div>
                <div id="pos-cart" class="flex-1 p-4 space-y-2 overflow-y-auto max-h-[380px]"><p class="text-sm text-slate-500 text-center py-8">Keranjang kosong</p></div>
                <div class="border-t border-white/10 p-4 space-y-3">
                    <input id="pos-nama" placeholder="Nama pelanggan" class="w-full bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none focus:border-sky-400/30" autocomplete="off">
                    <div class="flex gap-2">
                        <select id="pos-metode" class="flex-1 bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none"><option value="Tunai" class="bg-slate-900">Tunai</option><option value="QRIS" class="bg-slate-900">QRIS</option><option value="Transfer" class="bg-slate-900">Transfer</option></select>
                        <select id="pos-meja" class="w-28 bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none"><option class="bg-slate-900">Dine-in</option><option class="bg-slate-900">Takeaway</option></select>
                    </div>
                    <div class="rounded-2xl bg-white/[.04] border border-white/10 px-4 py-3 flex justify-between items-center"><span class="text-xs font-bold text-white">Total</span><span id="pos-total" class="font-black text-white">Rp 0</span></div>
                    <button onclick="posCheckout()" class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-purple-600 px-5 py-3.5 text-sm font-extrabold text-white shadow-glow hover:opacity-95 active:scale-[0.99] transition">Buat Pesanan → Konfirmasi</button>
                    <p class="text-[11px] text-center text-slate-500">Pesanan masuk antrean Konfirmasi Pembayaran</p>
                </div>
            </div>
        </div>
    </div>

    <div id="panel-konfirmasi" class="hidden space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="rounded-[1.5rem] border border-amber-400/20 bg-amber-500/10 p-4"><p class="text-[11px] font-bold tracking-widest text-amber-300 uppercase">Menunggu</p><p id="k-stat-menunggu" class="text-2xl font-black text-white mt-1">3</p><p class="text-xs text-amber-200/70">perlu konfirmasi</p></div>
            <div class="rounded-[1.5rem] border border-emerald-400/20 bg-emerald-500/10 p-4"><p class="text-[11px] font-bold tracking-widest text-emerald-300 uppercase">Selesai Hari Ini</p><p id="k-stat-selesai" class="text-2xl font-black text-white mt-1">0</p><p class="text-xs text-emerald-200/70">pembayaran lunas</p></div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/[.035] p-4"><p class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">Pendapatan Hari Ini</p><p id="k-stat-pendapatan" class="text-2xl font-black text-white mt-1">Rp 0</p><p class="text-xs text-slate-500">dari yang sudah bayar</p></div>
        </div>
        <div class="rounded-[1.7rem] border border-white/10 bg-white/[.035] p-4">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
                <h2 class="font-extrabold text-white">Antrean Pesanan</h2>
                <div class="flex gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64"><i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i><input id="k-search" placeholder="Cari nama / kode..." class="w-full bg-white/[.04] border border-white/10 text-white rounded-xl pl-9 pr-3 py-2.5 text-xs outline-none"></div>
                    <select id="k-filter" class="bg-white/[.04] border border-white/10 text-white text-xs rounded-xl px-3 py-2.5 outline-none"><option value="semua" class="bg-slate-900">Semua</option><option value="menunggu" class="bg-slate-900">Menunggu</option><option value="selesai" class="bg-slate-900">Selesai</option></select>
                </div>
            </div>
            <div id="k-list" class="mt-4 space-y-3"></div>
            <div id="k-empty" class="hidden text-center py-10 text-sm text-slate-500">Tidak ada pesanan</div>
        </div>
        <div id="k-toast" class="hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-50 px-4 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-bold shadow-lg flex items-center gap-2"><i class="fa-solid fa-check-circle"></i><span id="k-toast-msg">Berhasil</span></div>
    </div>
</div>
<script>
const menus=[{id:1,nama:'Es Teh Manis',harga:5000},{id:2,nama:'Nasi Goreng',harga:20000},{id:3,nama:'Ayam Bakar',harga:25000},{id:4,nama:'Mie Goreng',harga:18000},{id:5,nama:'Kopi Hitam',harga:8000},{id:6,nama:'Jus Alpukat',harga:12000}];
let cart=[];
let pendingOrders=[
    {kode:'A15',nama:'Dina',items:'2x Nasi Goreng, 1x Es Teh',total:45000,metode:'Tunai',status:'menunggu',waktu:'10:48'},
    {kode:'A16',nama:'Budi',items:'1x Ayam Bakar, 2x Es Teh',total:35000,metode:'QRIS',status:'menunggu',waktu:'10:52'},
    {kode:'A17',nama:'Siti',items:'1x Mie Goreng, 1x Kopi Hitam',total:26000,metode:'Tunai',status:'menunggu',waktu:'10:55'},
];
let counter=18;
function rupiah(n){return 'Rp '+Number(n).toLocaleString('id-ID')}
function switchKasirTab(tab){
    const isPos=tab==='pos';
    document.getElementById('panel-pos').classList.toggle('hidden',!isPos);
    document.getElementById('panel-konfirmasi').classList.toggle('hidden',isPos);
    const bPos=document.getElementById('btn-tab-pos'), bKon=document.getElementById('btn-tab-konfirmasi');
    if(isPos){bPos.className='px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 bg-white text-slate-900 shadow'; bKon.className='px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 text-slate-400 hover:text-white';}
    else {bKon.className='px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 bg-white text-slate-900 shadow'; bPos.className='px-4 py-2.5 rounded-full text-xs font-extrabold transition flex items-center gap-2 text-slate-400 hover:text-white';}
    const url=new URL(location.href); url.searchParams.set('tab',tab); history.replaceState(null,'',url);
    try{localStorage.setItem('kasir_tab',tab);}catch(e){}
    if(!isPos) renderKonfirmasi();
}
function renderMenus(f=''){
    const g=document.getElementById('pos-grid'); g.innerHTML='';
    menus.filter(m=>m.nama.toLowerCase().includes(f.toLowerCase())).forEach(m=>{
        const q=(cart.find(c=>c.id===m.id)?.qty)||0;
        g.innerHTML+=`<div onclick="addCart(${m.id})" class="rounded-2xl border border-white/10 bg-white/[.04] p-4 cursor-pointer hover:border-sky-400/30 hover:bg-white/[.06] transition"><div class="w-full h-20 rounded-xl bg-white/[.03] flex items-center justify-center text-slate-500"><i class="fa-solid fa-utensils"></i></div><p class="text-xs font-bold text-white mt-2">${m.nama}</p><p class="text-sm font-black text-sky-300">${rupiah(m.harga)}</p>${q?`<span class="mt-2 inline-flex text-xs bg-sky-500 text-white px-2 py-0.5 rounded-full font-bold">${q} di keranjang</span>`:''}</div>`;
    });
}
function addCart(id){const e=cart.find(c=>c.id===id); if(e) e.qty++; else {const m=menus.find(x=>x.id===id); cart.push({id:m.id,nama:m.nama,harga:m.harga,qty:1});} renderCart(); renderMenus(document.getElementById('pos-search').value);}
function inc(id){const c=cart.find(x=>x.id===id); c.qty++; renderCart(); renderMenus(document.getElementById('pos-search').value);}
function dec(id){const i=cart.findIndex(x=>x.id===id); if(cart[i].qty>1) cart[i].qty--; else cart.splice(i,1); renderCart(); renderMenus(document.getElementById('pos-search').value);}
function clearCart(){cart=[]; renderCart(); renderMenus(document.getElementById('pos-search').value);}
function renderCart(){
    const el=document.getElementById('pos-cart'); const total=cart.reduce((s,c)=>s+c.harga*c.qty,0); const cnt=cart.reduce((s,c)=>s+c.qty,0);
    document.getElementById('pos-total').textContent=rupiah(total); document.getElementById('pos-count').textContent=cnt+' item';
    if(!cart.length){el.innerHTML='<p class="text-sm text-slate-500 text-center py-8">Keranjang kosong</p>';return;}
    el.innerHTML=cart.map(c=>`<div class="flex items-center gap-3 rounded-2xl bg-white/[.03] border border-white/[.06] p-3"><div class="flex-1 min-w-0"><p class="text-xs font-bold text-white truncate">${c.nama}</p><p class="text-[11px] text-sky-300">${rupiah(c.harga)} x ${c.qty} = ${rupiah(c.harga*c.qty)}</p></div><div class="flex items-center gap-1"><button onclick="dec(${c.id})" class="w-7 h-7 rounded-lg bg-rose-500/15 text-rose-300 hover:bg-rose-500/25">-</button><span class="w-7 text-center text-xs font-black text-white">${c.qty}</span><button onclick="inc(${c.id})" class="w-7 h-7 rounded-lg bg-sky-500/15 text-sky-300 hover:bg-sky-500/25">+</button></div></div>`).join('');
}
function posCheckout(){
    if(!cart.length) return alert('Keranjang kosong');
    const nama=document.getElementById('pos-nama').value.trim()||'Tamu';
    const metode=document.getElementById('pos-metode').value;
    const total=cart.reduce((s,c)=>s+c.harga*c.qty,0);
    const items=cart.map(c=>`${c.qty}x ${c.nama}`).join(', ');
    const kode='A'+String(counter++).padStart(2,'0');
    const jam=new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
    pendingOrders.unshift({kode,nama,items,total,metode,status:'menunggu',waktu:jam});
    cart=[]; document.getElementById('pos-nama').value=''; renderCart(); renderMenus('');
    toast('Pesanan #'+kode+' masuk antrean');
    switchKasirTab('konfirmasi');
}
function toast(msg){
    const t=document.getElementById('k-toast'); document.getElementById('k-toast-msg').textContent=msg; t.classList.remove('hidden');
    clearTimeout(t._tm); t._tm=setTimeout(()=>t.classList.add('hidden'),2200);
}
function renderKonfirmasi(){
    const q=(document.getElementById('k-search').value||'').toLowerCase();
    const f=document.getElementById('k-filter').value;
    let list=[...pendingOrders];
    if(f!=='semua') list=list.filter(o=>o.status===f);
    if(q) list=list.filter(o=> (o.nama+o.kode+o.items).toLowerCase().includes(q));
    const menunggu=pendingOrders.filter(o=>o.status==='menunggu').length;
    const selesai=pendingOrders.filter(o=>o.status==='selesai').length;
    const pendapatan=pendingOrders.filter(o=>o.status==='selesai').reduce((s,o)=>s+o.total,0);
    document.getElementById('k-stat-menunggu').textContent=menunggu;
    document.getElementById('k-stat-selesai').textContent=selesai;
    document.getElementById('k-stat-pendapatan').textContent=rupiah(pendapatan);
    document.getElementById('badge-menunggu').textContent=menunggu;
    document.getElementById('badge-menunggu').classList.toggle('hidden',menunggu===0);
    const el=document.getElementById('k-list'), empty=document.getElementById('k-empty');
    if(!list.length){el.innerHTML=''; empty.classList.remove('hidden'); return;}
    empty.classList.add('hidden');
    el.innerHTML=list.map(o=>`
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl border ${o.status==='menunggu'?'border-amber-400/20 bg-amber-500/10':'border-emerald-400/20 bg-emerald-500/10'}">
            <div class="min-w-0">
                <p class="font-extrabold text-white text-sm">Antrian #${o.kode} — ${o.nama} <span class="ml-2 text-[10px] px-2 py-0.5 rounded-full ${o.status==='menunggu'?'bg-amber-500 text-white':'bg-emerald-500 text-white'}">${o.status}</span></p>
                <p class="text-xs text-slate-300 mt-1 truncate">${o.items}</p>
                <p class="text-[11px] text-slate-400 mt-1">${o.metode} · ${o.waktu} · <span class="font-black text-white">${rupiah(o.total)}</span></p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                ${o.status==='menunggu'
                    ? `<button onclick="konfirmasiBayar('${o.kode}')" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold">Telah Bayar</button><button onclick="batalPesanan('${o.kode}')" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-rose-500/20 text-slate-300 hover:text-rose-300 border border-white/10 text-xs font-bold">Batal</button>`
                    : `<span class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 text-emerald-300 text-xs font-bold"><i class="fa-solid fa-check"></i> Lunas</span><button onclick="hapusPesanan('${o.kode}')" class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 text-xs">Hapus</button>`}
            </div>
        </div>`).join('');
}
function konfirmasiBayar(kode){const o=pendingOrders.find(x=>x.kode===kode); if(!o) return; o.status='selesai'; renderKonfirmasi(); toast('Pembayaran #'+kode+' dikonfirmasi');}
function batalPesanan(kode){if(!confirm('Batalkan pesanan #'+kode+'?')) return; pendingOrders=pendingOrders.filter(x=>x.kode!==kode); renderKonfirmasi(); toast('Pesanan #'+kode+' dibatalkan');}
function hapusPesanan(kode){pendingOrders=pendingOrders.filter(x=>x.kode!==kode); renderKonfirmasi();}
document.getElementById('pos-search').addEventListener('input',e=>renderMenus(e.target.value));
document.getElementById('k-search').addEventListener('input',renderKonfirmasi);
document.getElementById('k-filter').addEventListener('change',renderKonfirmasi);
(function initTab(){
    const p=new URLSearchParams(location.search).get('tab');
    let tab=p||''; try{ if(!tab) tab=localStorage.getItem('kasir_tab')||'pos'; }catch(e){tab='pos';}
    if(tab!=='pos' && tab!=='konfirmasi') tab='pos';
    switchKasirTab(tab);
})();
renderMenus(); renderCart(); renderKonfirmasi();
</script>
<?= $this->endSection() ?>
