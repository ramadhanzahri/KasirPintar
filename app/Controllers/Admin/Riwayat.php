<?php

namespace App\Controllers\Admin;

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
            $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
            $currentHour = (int) $now->format('H');
            $startTime = ($currentHour < 2) ? date('Y-m-d 02:00:00', strtotime('-1 day')) : date('Y-m-d 02:00:00');
            $endTime = ($currentHour < 2) ? date('Y-m-d 01:59:59') : date('Y-m-d 01:59:59', strtotime('+1 day'));
            $builder->where('tb_pesanan.updated_at >=', $startTime)
                    ->where('tb_pesanan.updated_at <=', $endTime);
        }

        $riwayat = $builder->orderBy('tb_pesanan.updated_at', 'DESC')->get()->getResultArray();

        // Pendapatan hari ini
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $startHariIni = ($now->format('H') < 2) ? date('Y-m-d 02:00:00', strtotime('-1 day')) : date('Y-m-d 02:00:00');
        $endHariIni = ($now->format('H') < 2) ? date('Y-m-d 01:59:59') : date('Y-m-d 01:59:59', strtotime('+1 day'));
        
        $pendapatanHariIni = $db->table('tb_pesanan')
            ->selectSum('total_harga')
            ->where('status_pembayaran', 'selesai')
            ->where('updated_at >=', $startHariIni)
            ->where('updated_at <=', $endHariIni)
            ->get()->getRowArray()['total_harga'] ?? 0;

        $totalPendapatan = 0;
        $totalPesanan = count($riwayat);

        foreach ($riwayat as &$r) {
            $r['items'] = $this->itemModel->getByPesanan($r['kode_pesanan']);
            $totalPendapatan += $r['total_harga'];
        }
        unset($r);

        $data = [
            'title'               => 'Riwayat Transaksi Admin',
            'riwayat'             => $riwayat,
            'start_date'          => $startDate,
            'start_time'          => $startTime,
            'end_date'            => $endDate,
            'end_time'            => $endTime,
            'total_pesanan'       => $totalPesanan,
            'total_pendapatan'    => $totalPendapatan,
            'pendapatan_hari_ini' => $pendapatanHariIni
        ];

        return view('admin/riwayat', $data);
    }
}