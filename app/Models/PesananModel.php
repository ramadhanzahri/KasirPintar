<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'tb_pesanan';
    protected $primaryKey       = 'kode_pesanan';
    protected $returnType       = 'array';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'kode_pesanan',
        'nomor_antrian',
        'nama_pemesan',
        'total_harga',
        'uang_bayar',
        'kembalian',
        'status_pembayaran',
        'catatan',
        'metode_pembayaran',
        'kasir_id',
        'kasir_nama',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPending(): array
    {
        return $this->where('status_pembayaran', 'pending')
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    public function getSelesaiHariIni(): int
    {
        return $this->where('status_pembayaran', 'selesai')
            ->where('DATE(updated_at)', date('Y-m-d'))
            ->countAllResults();
    }

    public function getPendapatanHariIni(): int
    {
        $row = $this->selectSum('total_harga')
            ->where('status_pembayaran', 'selesai')
            ->where('DATE(updated_at)', date('Y-m-d'))
            ->first();

        return (int) ($row['total_harga'] ?? 0);
    }

    public function getRiwayatByTanggal(?string $startDate = null, ?string $endDate = null): array
    {
        $builder = $this->where('status_pembayaran', 'selesai');

        if (! empty($startDate)) {
            $builder->where('DATE(updated_at) >=', $startDate);
        }

        if (! empty($endDate)) {
            $builder->where('DATE(updated_at) <=', $endDate);
        }

        return $builder
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }

    public function generateKode(): string
    {
        do {
            $kode = 'ORD-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $exists = $this->where('kode_pesanan', $kode)->first();
        } while ($exists);

        return $kode;
    }

    public function generateNomorAntrian(): string
    {
        $today = date('Y-m-d');

        $last = $this->select('nomor_antrian')
            ->where('DATE(created_at)', $today)
            ->where('nomor_antrian IS NOT NULL', null, false)
            ->orderBy('created_at', 'DESC')
            ->first();

        $nextNumber = 1;

        if ($last && ! empty($last['nomor_antrian'])) {
            $number = (int) preg_replace('/[^0-9]/', '', $last['nomor_antrian']);
            $nextNumber = $number + 1;
        }

        do {
            $nomor = 'A' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

            $exists = $this->where('DATE(created_at)', $today)
                ->where('nomor_antrian', $nomor)
                ->first();

            $nextNumber++;
        } while ($exists);

        return $nomor;
    }
}