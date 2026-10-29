<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\PesananItemModel;

class Riwayat extends BaseController
{
    protected PesananItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new PesananItemModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();
        
        $startDate = $request->getGet('start_date');
        $startTime = $request->getGet('start_time');
        $endDate = $request->getGet('end_date');
        $endTime = $request->getGet('end_time');
        
        $builder = $db->table('tb_pesanan')
            ->where('tb_pesanan.status_pembayaran', 'selesai');

        if (!empty($startDate) || !empty($endDate)) {
            if (!empty($startDate)) {
                $startDateTime = $startDate . (!empty($startTime) ? ' ' . $startTime . ':00' : ' 00:00:00');
                $builder->where('tb_pesanan.updated_at >=', $startDateTime);
            }

            if (!empty($endDate)) {
                $endDateTime = $endDate . (!empty($endTime) ? ' ' . $endTime . ':59' : ' 23:59:59');
                $builder->where('tb_pesanan.updated_at <=', $endDateTime);
            }
        } else {
            // Default: Hari ini (02:00 s/d 01:59 besok)
            $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
            $currentHour = (int) $now->format('H');
            if ($currentHour < 2) {
                $startTime = date('Y-m-d 02:00:00', strtotime('-1 day'));
                $endTime   = date('Y-m-d 01:59:59');
            } else {
                $startTime = date('Y-m-d 02:00:00');
                $endTime   = date('Y-m-d 01:59:59', strtotime('+1 day'));
            }
            $builder->where('tb_pesanan.updated_at >=', $startTime)
                    ->where('tb_pesanan.updated_at <=', $endTime);
        }

        $riwayat = $builder->orderBy('tb_pesanan.updated_at', 'DESC')->get()->getResultArray();

        $totalPendapatan = 0;
        $totalPesanan = count($riwayat);

        foreach ($riwayat as &$r) {
            $r['items'] = $this->itemModel->getByPesanan($r['kode_pesanan']);
            $totalPendapatan += $r['total_harga'];
        }
        unset($r);

        $data = [
            'title'            => 'Riwayat Transaksi',
            'riwayat'          => $riwayat,
            'start_date'       => $startDate,
            'start_time'       => $startTime,
            'end_date'         => $endDate,
            'end_time'         => $endTime,
            'total_pesanan'    => $totalPesanan,
            'total_pendapatan' => $totalPendapatan
        ];

        return view('kasir/riwayat', $data);
    }
}