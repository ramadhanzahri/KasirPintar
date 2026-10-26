<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\RiwayatStokModel;

class RiwayatStok extends BaseController
{
    protected MenuModel $menuModel;
    protected RiwayatStokModel $riwayatModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->riwayatModel = new RiwayatStokModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Riwayat Stok',
            'riwayat' => $this->riwayatModel
                ->select('tb_riwayat_stok.*, tb_menu.nama_menu')
                ->join('tb_menu', 'tb_menu.id = tb_riwayat_stok.menu_id')
                ->orderBy('tanggal', 'DESC')
                ->findAll(),
        ];

        return view('admin/riwayat_stok/index', $data);
    }

    public function store()
    {
        $rules = [
            'menu_id' => 'required|integer',
            'stok'    => 'required|integer',
            'tanggal' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->riwayatModel->insert([
            'menu_id' => $this->request->getPost('menu_id'),
            'stok'    => $this->request->getPost('stok'),
            'tanggal' => $this->request->getPost('tanggal'),
        ]);

        // Insert juga ke tb_stok agar stok menu bertambah
        $db = \Config\Database::connect();
        $db->table('tb_stok')->insert([
            'menu_id' => $this->request->getPost('menu_id'),
            'stok'    => $this->request->getPost('stok'),
            'tanggal' => $this->request->getPost('tanggal'),
        ]);

        return redirect()->to(base_url('admin/menu'))->with('success', 'Stok berhasil ditambahkan.');
    }

    public function delete(int $id)
    {
        $riwayat = $this->riwayatModel->find($id);

        if (!$riwayat) {
            return redirect()->to(base_url('admin/riwayat-stok'))->with('error', 'Riwayat stok tidak ditemukan.');
        }

        if ($riwayat['tanggal'] !== date('Y-m-d')) {
            return redirect()->to(base_url('admin/riwayat-stok'))->with('error', 'Hanya riwayat stok hari ini yang dapat dihapus.');
        }

        $db = \Config\Database::connect();

        // Hapus dari tb_riwayat_stok
        $this->riwayatModel->delete($id);

        // Hapus dari tb_stok yang memiliki data sama
        $db->table('tb_stok')
           ->where('menu_id', $riwayat['menu_id'])
           ->where('stok', $riwayat['stok'])
           ->where('tanggal', $riwayat['tanggal'])
           ->limit(1)
           ->delete();

        return redirect()->to(base_url('admin/riwayat-stok'))->with('success', 'Riwayat stok berhasil dihapus.');
    }
}
