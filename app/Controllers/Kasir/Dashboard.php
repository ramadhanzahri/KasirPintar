<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\PesananItemModel;
use App\Models\StokModel;

class Dashboard extends BaseController
{
    protected PesananModel $pesananModel;
    protected PesananItemModel $itemModel;
    protected StokModel $stokModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->itemModel = new PesananItemModel();
        $this->stokModel = new StokModel();
    }

    public function index()
    {
        $pending = $this->pesananModel->getPending();
        $last_kode = '';

        foreach ($pending as &$p) {
            $p['items'] = $this->itemModel->getByPesanan($p['kode_pesanan']);

            if ((string) $p['kode_pesanan'] > $last_kode) {
                $last_kode = (string) $p['kode_pesanan'];
            }
        }

        unset($p);

        // LOGIKA RESET JAM 02:00 PAGI
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $currentHour = (int)$now->format('H');

        if ($currentHour < 2) {
            // Jika jam 00:00 - 01:59, maka "hari ini" adalah dari kemarin jam 02:00 s/d besok (hari ini) jam 01:59
            $startTime = date('Y-m-d 02:00:00', strtotime('-1 day'));
            $endTime   = date('Y-m-d 01:59:59');
        } else {
            // Jika jam 02:00 ke atas, maka "hari ini" adalah dari tadi jam 02:00 s/d besok jam 01:59
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

        $data = [
            'title' => 'Antrean Pesanan',
            'pesanan_pending' => $pending,
            'riwayat_hari_ini' => $riwayatHariIni,
            'total_pending' => count($pending),
            'total_selesai' => $totalSelesai,
            'pendapatan' => $pendapatan,
            'last_id' => $last_kode,
            'last_payment_time' => date('Y-m-d H:i:s'),
        ];

        return view('kasir/dashboard', $data);
    }

    public function telahBayar(string $kodePesanan)
    {
        try {
            $isAjax = $this->request->isAJAX();
            $pesanan = $this->pesananModel->find($kodePesanan);

            if (! $pesanan) {
                if ($isAjax) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
                }
                return redirect()->to(base_url('kasir/dashboard'))->with('error', 'Pesanan tidak ditemukan.');
            }

            $nomorAntrian = $pesanan['nomor_antrian'] ?? '-';

            $sessionKasirId   = (int) (session()->get('id') ?? 0);
            $sessionKasirNama = (string) (session()->get('nama_lengkap') ?? session()->get('username') ?? '');

            if (($pesanan['status_pembayaran'] ?? 'pending') === 'selesai') {
                if (empty($pesanan['kasir_id']) && $sessionKasirId > 0) {
                    $this->pesananModel->update($kodePesanan, [
                        'kasir_id'   => $sessionKasirId,
                        'kasir_nama' => $sessionKasirNama !== '' ? $sessionKasirNama : null,
                    ]);
                    $pesanan = $this->pesananModel->find($kodePesanan) ?? $pesanan;
                }
                $pesanan['items']             = $this->itemModel->getByPesanan($pesanan['kode_pesanan']);
                $pesanan['status_pembayaran'] = $pesanan['status_pembayaran'] ?? 'pending';
                $pesanan['metode_label']      = $this->formatMetodePembayaran($pesanan['metode_pembayaran'] ?? '-');
                if ($isAjax) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Pembayaran antrian ' . $nomorAntrian . ' sudah tercatat.', 'data' => $pesanan]);
                }
                return redirect()->to(base_url('kasir/dashboard'))->with('success', 'Pembayaran antrian ' . $nomorAntrian . ' sudah tercatat.');
            }

            $updateData = ['status_pembayaran' => 'selesai'];
            if (empty($pesanan['kasir_id']) && empty($pesanan['kasir_nama'])) {
                $updateData['kasir_id']   = $sessionKasirId > 0 ? $sessionKasirId : null;
                $updateData['kasir_nama'] = $sessionKasirNama !== '' ? $sessionKasirNama : null;
            }
            $this->pesananModel->update($kodePesanan, $updateData);

            $pesanan = $this->pesananModel->find($kodePesanan);
            if (! $pesanan) {
                throw new \RuntimeException('Gagal membaca pesanan setelah update.');
            }

            $pesanan['items']             = $this->itemModel->getByPesanan($pesanan['kode_pesanan']);
            $pesanan['waktu']             = ! empty($pesanan['updated_at']) ? date('H:i', strtotime($pesanan['updated_at'])) : date('H:i');
            $pesanan['created_waktu']     = ! empty($pesanan['created_at']) ? date('H:i', strtotime($pesanan['created_at'])) : '-';
            $pesanan['total_format']      = number_format((int) ($pesanan['total_harga'] ?? 0), 0, ',', '.');
            $pesanan['status_pembayaran'] = $pesanan['status_pembayaran'] ?? 'pending';
            $pesanan['metode_label']      = $this->formatMetodePembayaran($pesanan['metode_pembayaran'] ?? '-');
            $message = 'Pembayaran ' . $pesanan['metode_label'] . ' antrian ' . $nomorAntrian . ' diterima.';

            if ($isAjax) {
                return $this->response->setJSON(['success' => true, 'message' => $message, 'data' => $pesanan]);
            }
            return redirect()->to(base_url('kasir/dashboard'))->with('success', $message);
        } catch (\Throwable $e) {
            log_message('error', 'telahBayar error kode=' . $kodePesanan . ' : ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            }
            return redirect()->to(base_url('kasir/dashboard'))->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function cek_baru($last_kode = '')
    {
        try {
            $last_kode = (string) $last_kode;

            $semuaPending = $this->pesananModel->getPending();
            $pesananBaru = [];
            $new_last_kode = $last_kode;
            $seen = [];

            foreach ($semuaPending as $p) {
                $kode = (string) ($p['kode_pesanan'] ?? '');
                if ($kode === '' || $kode === $last_kode || isset($seen[$kode])) {
                    continue;
                }
                if ($last_kode !== '' && strcmp($kode, $last_kode) <= 0) {
                    continue;
                }
                $p['items'] = $this->itemModel->getByPesanan($kode);
                $p['waktu'] = ! empty($p['created_at']) ? date('H:i', strtotime($p['created_at'])) : date('H:i');
                $p['total_format'] = number_format((int) ($p['total_harga'] ?? 0), 0, ',', '.');
                $p['status_pembayaran'] = $p['status_pembayaran'] ?? 'pending';
                $p['metode_label'] = $this->formatMetodePembayaran($p['metode_pembayaran'] ?? '-');

                $pesananBaru[] = $p;
                $seen[$kode] = true;

                if (strcmp($kode, $new_last_kode) > 0) {
                    $new_last_kode = $kode;
                }
            }

            if (! empty($pesananBaru)) {
                return $this->response->setJSON([
                    'ada' => true,
                    'data' => $pesananBaru,
                    'last_id' => $new_last_kode,
                ]);
            }

            return $this->response->setJSON([
                'ada' => false,
                'last_id' => $new_last_kode,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'CEK PESANAN BARU ERROR: ' . $e->getMessage());

            return $this->response->setJSON([
                'ada' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function cek_pembayaran_berhasil()
    {
        try {
            $lastTime = $this->request->getGet('last_time');

            if (empty($lastTime)) {
                $lastTime = date('Y-m-d H:i:s');
            }

            $hari_ini = date('Y-m-d');

            $pesananLunas = $this->pesananModel
                ->where('DATE(updated_at)', $hari_ini)
                ->where('updated_at >', $lastTime)
                ->where('status_pembayaran', 'selesai')
                ->orderBy('updated_at', 'ASC')
                ->findAll();

            $data = [];
            $newLastTime = $lastTime;

            foreach ($pesananLunas as $p) {
                $p['items'] = $this->itemModel->getByPesanan($p['kode_pesanan']);
                $p['waktu'] = ! empty($p['updated_at']) ? date('H:i', strtotime($p['updated_at'])) : date('H:i');
                $p['created_waktu'] = ! empty($p['created_at']) ? date('H:i', strtotime($p['created_at'])) : '-';
                $p['total_format'] = number_format((int) ($p['total_harga'] ?? 0), 0, ',', '.');
                $p['status_pembayaran'] = $p['status_pembayaran'] ?? 'pending';
                $p['metode_label'] = $this->formatMetodePembayaran($p['metode_pembayaran'] ?? '-');

                $data[] = $p;

                if (! empty($p['updated_at']) && strtotime($p['updated_at']) > strtotime($newLastTime)) {
                    $newLastTime = $p['updated_at'];
                }
            }

            if (! empty($data)) {
                return $this->response->setJSON([
                    'ada' => true,
                    'data' => $data,
                    'last_time' => $newLastTime,
                ]);
            }

            return $this->response->setJSON([
                'ada' => false,
                'last_time' => $lastTime,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'CEK PEMBAYARAN BERHASIL ERROR: ' . $e->getMessage());

            return $this->response->setJSON([
                'ada' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function batal(string $kodePesanan)
    {
        try {
            $pesanan = $this->pesananModel->find($kodePesanan);
            if (! $pesanan) {
                return $this->response->setJSON(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
            }
            if (($pesanan['status_pembayaran'] ?? 'pending') !== 'pending') {
                return $this->response->setJSON(['success' => false, 'message' => 'Hanya pesanan pending yang bisa dibatalkan.']);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $items = $this->itemModel->getByPesanan($kodePesanan);
            foreach ($items as $it) {
                $menuId = $it['menu_id'] ?? null;
                if (! $menuId) continue;
                // Kembalikan stok hanya jika menu punya catatan stok (bukan unlimited)
                if (! is_null($this->stokModel->getTotal((int) $menuId))) {
                    $this->stokModel->tambah((int) $menuId, (int) ($it['qty'] ?? 0), date('Y-m-d'));
                }
            }

            $this->itemModel->where('kode_pesanan', $kodePesanan)->delete();
            $this->pesananModel->delete($kodePesanan);

            $db->transComplete();
            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membatalkan pesanan.']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Pesanan ' . ($pesanan['nomor_antrian'] ?? $kodePesanan) . ' dibatalkan.']);
        } catch (\Throwable $e) {
            log_message('error', 'batal error kode=' . $kodePesanan . ' : ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(string $kodePesanan)
    {
        return $this->response->setJSON(['success' => true]);
    }

    private function formatMetodePembayaran(string $metode): string
    {
        return match ($metode) {
            'tunai' => 'Tunai / COD',
            'qris' => 'QRIS / E-Wallet',
            default => ucfirst($metode),
        };
    }
}