<?php

namespace App\Models;

use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table            = 'tb_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['menu_id', 'stok', 'tanggal'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
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

    public function getTotal(int $menuId): ?int
    {
        $row = $this->selectSum('stok') ->where('menu_id', $menuId) ->first();

        if (! $row || ! isset($row['stok'])) {
            return null;
        }

        return (int) $row['stok'];
    }

    public function tambah(int $menuId, int $jumlah, string $tanggal): void
    {
        $this->insert([
            'menu_id' => $menuId,
            'stok' => $jumlah,
            'tanggal' => $tanggal,
        ]);
    }

    public function kurang(int $menuId, int $jumlah, ?string $tanggal = null): void
    {
        if ($jumlah <= 0) {
            return;
        }

        $this->insert([
            'menu_id' => $menuId,
            'stok' => -$jumlah,
            'tanggal' => $tanggal ?? date('Y-m-d'),
        ]);
    }
}
