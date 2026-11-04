<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RiwayatStokModel;

class RiwayatStokResetFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = \Config\Database::connect();

        $lastReset = $db->table('tb_pengaturan')->where('setting_key', 'riwayat_stok_last_reset')->get()->getRowArray();

        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $todayResetTime = clone $now;
        $todayResetTime->setTime(2, 0, 0);

        $lastResetTime = $lastReset ? new \DateTime($lastReset['setting_value'], new \DateTimeZone('Asia/Jakarta')) : null;

        if ($now >= $todayResetTime && (!$lastResetTime || $lastResetTime < $todayResetTime)) {
            $model = new RiwayatStokModel();
            $model->truncate();

            if ($lastReset) {
                $db->table('tb_pengaturan')->where('setting_key', 'riwayat_stok_last_reset')->update([
                    'setting_value' => $now->format('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $db->table('tb_pengaturan')->insert([
                    'setting_key' => 'riwayat_stok_last_reset',
                    'setting_value' => $now->format('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
