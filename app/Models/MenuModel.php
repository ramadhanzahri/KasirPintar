<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'tb_menu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_menu', 'harga', 'gambar', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama_menu' => 'required|min_length[2]|max_length[100]',
        'harga'     => 'required|integer|greater_than[0]',
        'is_active' => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'nama_menu' => [
            'required'   => 'Nama menu wajib diisi.',
            'min_length' => 'Nama menu minimal 2 karakter.',
        ],
        'harga' => [
            'required'     => 'Harga wajib diisi.',
            'integer'      => 'Harga harus berupa angka.',
            'greater_than' => 'Harga harus lebih dari 0.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    public function getAll(): array
    {
        return $this->select('tb_menu.*, SUM(tb_stok.stok) as total_stok')
                    ->join('tb_stok', 'tb_stok.menu_id = tb_menu.id', 'left')
                    ->groupBy('tb_menu.id')
                    ->orderBy('tb_menu.id', 'ASC')
                    ->findAll();
    }

    public function getAktif(): array
    {
        return $this->select('tb_menu.*, SUM(tb_stok.stok) as stok')
                    ->join('tb_stok', 'tb_stok.menu_id = tb_menu.id', 'left')
                    ->where('tb_menu.is_active', 1)
                    ->groupBy('tb_menu.id')
                    ->orderBy('tb_menu.nama_menu', 'ASC')
                    ->findAll();
    }

    public function getAvailable(): array
    {
        return $this->select('tb_menu.*, SUM(tb_stok.stok) as stok')
                    ->join('tb_stok', 'tb_stok.menu_id = tb_menu.id', 'left')
                    ->where('tb_menu.is_active', 1)
                    ->groupBy('tb_menu.id')
                    ->having('stok IS NULL OR stok > 0', null, false)
                    ->orderBy('tb_menu.nama_menu', 'ASC')
                    ->findAll();
    }
}