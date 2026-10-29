<?php

namespace App\Models;

use CodeIgniter\Commands\Database\CreateDatabase;
use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'tb_pesanan';
    protected $primaryKey       = 'kode_pesanan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_pesanan',
        'nomor_antrian',
        'nama_pemesan',
        'total_harga',
        'uang_bayar',
        'kembalian',
        'status',
        'catatan',
        'metode_pembayaran',
        'kasir_id',
        'kasir_nama',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

public function getPending(): array
{
    return $this->where('status', 'pending')
    ->orderBy('created_at', 'ASC') ->findAll();
}

public function getSelesaiHariIni(): int 
{return $this->where('status', 'selesai') ->where('DATE(updated_at)', date('Y-m-d')) ->countAllResults();}

public function getPendapatanHariIni(): int
{$row = $this->selectSum('total_harga') ->where('status', 'selesai') ->where('DATE(updated_at)', date('Y-m-d')) ->first();
return (int) ($row['total_harga'] ?? 0); }

public function getRiwayatByTanggal(?string $startDate = null, ?string $endData = null): array
{$builder = $this->where('status', 'selesai');

    if (! empty($startDate)) {
        $builder->where('DATE(updated_at) >=', $startDate);
    }

    if (! empty($endData)) {
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