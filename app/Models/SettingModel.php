<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'tb_pengaturan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'setting_key',
        'setting_value',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllSettings(): array
    {
        $rows = $this->findAll();

        $settings = [];

        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    public function getValue(string $key, ?string $default = null): ?string
    {
        $row = $this->where('setting_key', $key)->first();

        return $row['setting_value'] ?? $default;
    }

    public function setValue(string $key, ?string $value): bool
    {
        $row = $this->where('setting_key', $key)->first();

        if ($row) {
            return (bool) $this->update($row['id'], [
                'setting_value' => $value,
            ]);
        }

        return (bool) $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
        ]);
    }
}