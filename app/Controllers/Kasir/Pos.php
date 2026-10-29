<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\PesananModel;
use App\Models\PesananItemModel;
use App\Models\StokModel;

class Pos extends BaseController
{
    protected MenuModel        $menuModel;
    protected PesananModel     $pesananModel;
    protected PesananItemModel $itemModel;
    protected StokModel        $stokModel;

    public function __construct()
    {
        $this->menuModel    = new MenuModel();
        $this->pesananModel = new PesananModel();
        $this->itemModel    = new PesananItemModel();
        $this->stokModel    = new StokModel();
    }

    public function stok()
    {
        $menus = $this->menuModel
            ->select('tb_menu.id, tb_menu.nama_menu, tb_menu.is_active, SUM(tb_stok.stok) as stok')
            ->join('tb_stok', 'tb_stok.menu_id = tb_menu.id', 'left')
            ->groupBy('tb_menu.id')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $menus]);
    }

    public function index()
    {
        $menus = $this->menuModel->getAktif();

        $pending = $this->pesananModel->getPending();
        $last_kode = '';

        foreach ($pending as &$p) {
            $p['items'] = $this->itemModel->getByPesanan($p['kode_pesanan']);
            if ((string) $p['kode_pesanan'] > $last_kode) {
                $last_kode = (string) $p['kode_pesanan'];
            }
        }
        unset($p);

        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $currentHour = (int) $now->format('H');
        if ($currentHour < 2) {
            $startTime = date('Y-m-d 02:00:00', strtotime('-1 day'));
            $endTime   = date('Y-m-d 01:59:59');
        } else {
            $startTime = date('Y-m-d 02:00:00');
            $endTime   = date('Y-m-d 01:59:59', strtotime('+1 day'));
        }

        $pendapatan_row = $this->pesananModel
            ->selectSum('total_harga')
            ->where('updated_at >=', $startTime)
            ->where('updated_at <=', $endTime)
            ->where('status_pembayaran', 'selesai')
            ->first();
        $pendapatan = $pendapatan_row['total_harga'] ?? 0;

        $totalSelesai = $this->pesananModel
            ->where('status_pembayaran', 'selesai')
            ->where('updated_at >=', $startTime)
            ->where('updated_at <=', $endTime)
            ->countAllResults();

        $riwayatHariIni = $this->pesananModel
            ->where('created_at >=', $startTime)
            ->where('created_at <=', $endTime)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($riwayatHariIni as &$r) {
            $r['items'] = $this->itemModel->getByPesanan($r['kode_pesanan']);
        }
        unset($r);

        return view('kasir/pos', [
            'title'            => 'POS Pintar',
            'menus'            => $menus,
            'pesanan_pending'  => $pending,
            'riwayat_hari_ini' => $riwayatHariIni,
            'total_pending'    => count($pending),
            'total_selesai'    => $totalSelesai,
            'pendapatan'       => $pendapatan,
            'last_id'          => $last_kode,
            'last_payment_time'=> date('Y-m-d H:i:s'),
        ]);
    }

    public function submit()
    {
        $rules = [
            'nama_pemesan'   => 'required|min_length[2]|max_length[100]',
            'items'          => 'required',
            'payment_method' => 'required|in_list[tunai,qris]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $items = json_decode((string) $this->request->getPost('items'), true);

        if (empty($items) || ! is_array($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keranjang kosong.',
            ]);
        }

        $metodePembayaran = (string) $this->request->getPost('payment_method');
        $namaPemesan      = trim((string) $this->request->getPost('nama_pemesan'));
        $catatan          = trim((string) $this->request->getPost('catatan'));
        $uangBayar        = $this->request->getPost('uang_bayar');

        /*
         * Penting:
         * Harga tidak boleh percaya dari JavaScript.
         * Server ambil ulang harga dari database menu.
         */
        $cleanItems = [];
        $subTotal   = 0;

        foreach ($items as $item) {
            $menuId = (int) ($item['id'] ?? 0);
            $qty    = (int) ($item['qty'] ?? 0);

            if ($menuId <= 0 || $qty <= 0) {
                continue;
            }

            $menu = $this->menuModel->find($menuId);

            if (! $menu) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Salah satu menu tidak ditemukan.',
                ]);
            }

            if ((int) ($menu['is_active'] ?? 1) !== 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Menu "' . ($menu['nama_menu'] ?? '-') . '" sedang tidak aktif.',
                ]);
            }

            $stokTotal = $this->stokModel->getTotal($menuId);

            if (! is_null($stokTotal) && $stokTotal <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Menu "' . ($menu['nama_menu'] ?? '-') . '" sedang habis.',
                ]);
            }

            if (! is_null($stokTotal) && $stokTotal < $qty) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stok "' . ($menu['nama_menu'] ?? '-') . '" tidak mencukupi. Sisa: ' . $stokTotal,
                ]);
            }

            $harga = (int) ($menu['harga'] ?? 0);

            if ($harga < 0) {
                $harga = 0;
            }

        $cleanItems[] = [
            'id'        => $menuId,
            'nama_menu' => $menu['nama_menu'] ?? '-',
            'harga'     => $harga,
            'qty'       => $qty,
            'sub_total' => $harga * $qty,
        ];

            $subTotal += $harga * $qty;
        }

        if (empty($cleanItems) || $subTotal <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keranjang tidak valid.',
            ]);
        }

        $totalHarga = $subTotal;

        // Hitung kembalian jika tunai
        $kembalian = null;
        $finalUangBayar = null;
        if ($metodePembayaran === 'tunai') {
            if ($uangBayar === null || $uangBayar === '') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Uang bayar wajib diisi untuk pembayaran tunai.',
                ]);
            }
            $finalUangBayar = (int)$uangBayar;
            if ($finalUangBayar < $totalHarga) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Uang bayar kurang.',
                ]);
            }
            $kembalian = $finalUangBayar - $totalHarga;
        }

        $kodeP         = $this->pesananModel->generateKode();
        $nomorAntrian = $this->pesananModel->generateNomorAntrian();

        $kasirId   = (int) (session()->get('id') ?? 0);
        $kasirNama = (string) (session()->get('nama_lengkap') ?? session()->get('username') ?? '');

        $db = db_connect();
        $db->transStart();

        $this->pesananModel->insert([
            'kode_pesanan'      => $kodeP,
            'nomor_antrian'     => $nomorAntrian,
            'nama_pemesan'      => $namaPemesan,
            'total_harga'       => $totalHarga,
            'uang_bayar'        => $finalUangBayar,
            'kembalian'         => $kembalian,
            'catatan'           => $catatan !== '' ? $catatan : null,
            'status_pembayaran'   => 'pending',
            'metode_pembayaran' => $metodePembayaran,
            'kasir_id'          => $kasirId > 0 ? $kasirId : null,
            'kasir_nama'        => $kasirNama !== '' ? $kasirNama : null,
        ]);

        foreach ($cleanItems as $item) {
                $this->itemModel->insert([
                    'kode_pesanan' => $kodeP,
                    'menu_id'      => $item['id'],
                    'nama_menu'    => $item['nama_menu'],
                    'harga'        => $item['harga'],
                    'qty'          => $item['qty'],
                    'sub_total'    => $item['sub_total'],
                ]);

            // Potong stok hanya jika menu memiliki catatan stok (bukan unlimited)
            if (! is_null($this->stokModel->getTotal($item['id']))) {
                $this->stokModel->kurang($item['id'], $item['qty']);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.',
            ]);
        }

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Pesanan nomor antrian ' . $nomorAntrian . ' berhasil dibuat.',
            'kode_pesanan'  => $kodeP,
            'nomor_antrian' => $nomorAntrian,
            'receipt_data'  => [
                'kode_pesanan'      => $kodeP,
                'nomor_antrian'     => $nomorAntrian,
                'nama_pemesan'      => $namaPemesan,
                'total_harga'       => $totalHarga,
                'uang_bayar'        => $finalUangBayar,
                'kembalian'         => $kembalian,
                'metode_pembayaran' => $metodePembayaran,
                'metode_label'      => ucfirst($metodePembayaran),
                'waktu'             => date('d/m/Y H:i'),
                'catatan'           => $catatan,
                'kasir_nama'        => $kasirNama !== '' ? $kasirNama : '-',
                'items'             => $cleanItems
            ]
        ]);
    }
}