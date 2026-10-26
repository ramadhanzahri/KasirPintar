<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;

class Menu extends BaseController
{
    protected MenuModel $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Menu',
            'menus' => $this->menuModel->getAll(),
        ];

        return view('admin/menu/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Menu',
        ];

        return view('admin/menu/form', $data);
    }

    public function store()
    {
        $rules = [
            'nama_menu'   => 'required|min_length[2]|max_length[100]',
            'harga'       => 'required|integer|greater_than[0]',
            'is_active'   => 'required|in_list[0,1]',
            'gambar'      => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $gambar = $this->request->getFile('gambar');
        $namaGambar = null;

        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move(FCPATH . 'uploads/menu', $namaGambar);
        }

        $saved = $this->menuModel->insert([
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'harga'       => $this->request->getPost('harga'),
            'gambar'      => $namaGambar,
            'is_active'   => $this->request->getPost('is_active'),
        ]);

        if (!$saved) {
            return redirect()->back()->withInput()->with('errors', $this->menuModel->errors());
        }

        return redirect()->to(base_url('admin/menu'))->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->to(base_url('admin/menu'))->with('error', 'Menu tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Menu',
            'menu'  => $menu,
        ];

        return view('admin/menu/form', $data);
    }

    public function update(int $id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->to(base_url('admin/menu'))->with('error', 'Menu tidak ditemukan.');
        }

        $rules = [
            'nama_menu'   => 'required|min_length[2]|max_length[100]',
            'harga'       => 'required|integer|greater_than[0]',
            'is_active'   => 'required|in_list[0,1]',
            'gambar'      => 'permit_empty|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $gambar = $this->request->getFile('gambar');
        $namaGambar = $menu['gambar'];

        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            if ($namaGambar && file_exists(FCPATH . 'uploads/menu/' . $namaGambar)) {
                unlink(FCPATH . 'uploads/menu/' . $namaGambar);
            }
            $namaGambar = $gambar->getRandomName();
            $gambar->move(FCPATH . 'uploads/menu', $namaGambar);
        }

        $updated = $this->menuModel->update($id, [
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'harga'       => $this->request->getPost('harga'),
            'gambar'      => $namaGambar,
            'is_active'   => $this->request->getPost('is_active'),
        ]);

        if (!$updated) {
            return redirect()->back()->withInput()->with('errors', $this->menuModel->errors());
        }

        return redirect()->to(base_url('admin/menu'))->with('success', 'Menu berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->to(base_url('admin/menu'))->with('error', 'Menu tidak ditemukan.');
        }

        if ($menu['gambar'] && file_exists(FCPATH . 'uploads/menu/' . $menu['gambar'])) {
            unlink(FCPATH . 'uploads/menu/' . $menu['gambar']);
        }

        $this->menuModel->delete($id);

        return redirect()->to(base_url('admin/menu'))->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->to(base_url('admin/menu'))->with('error', 'Menu tidak ditemukan.');
        }

        $this->menuModel->update($id, [
            'is_active' => $menu['is_active'] == 1 ? 0 : 1,
        ]);

        return redirect()->to(base_url('admin/menu'))->with('success', 'Status menu diperbarui.');
    }

    public function toggleStok(int $id)
    {
        // Fitur ini perlu dirancang ulang dengan tabel tb_stok baru.
        // Untuk sementara dinonaktifkan atau sesuaikan dengan kebutuhan input stok baru.
        return redirect()->to(base_url('admin/menu'))->with('error', 'Fitur stok sedang dalam pembaruan.');
    }
}