<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatStokModel extends Model
{
    protected $table            = 'tb_riwayat_stok';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['menu_id', 'stok', 'tanggal'];

    protected $validationRules = [
        'menu_id' => 'required|integer',
        'stok'    => 'required|integer',
        'tanggal' => 'required|valid_date',
    ];

    protected $skipValidation = true;
}
