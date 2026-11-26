<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $users = $db->table('tb_user')
                    ->orderBy('role', 'ASC')
                    ->orderBy('nama_lengkap', 'ASC')
                    ->get()
                    ->getResultArray();

        $data = [
            'title' => 'Manajemen Akun',
            'users' => $users
        ];

        return view('admin/users', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        
        $password = (string)$this->request->getPost('password');
        
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role')
        ];

        $db->table('tb_user')->insert($data);
        
        return redirect()->to(base_url('admin/users'))->with('success', 'Akun baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $user = $db->table('tb_user')->where('id', $id)->get()->getRowArray();

        if (! $user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found'], 404);
        }

        if (($user['role'] ?? '') !== 'kasir') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Superadmin tidak dapat diedit'], 403);
        }

        return $this->response->setJSON($user);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $user = $db->table('tb_user')->where('id', $id)->get()->getRowArray();

        if (! $user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        if (($user['role'] ?? '') !== 'kasir') {
            return redirect()->to(base_url('admin/users'))->with('error', 'Akun superadmin tidak dapat diubah.');
        }
        
        $password = $this->request->getPost('password');
        
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'role'         => $this->request->getPost('role'),
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db->table('tb_user')->where('id', $id)->update($data);
        
        return redirect()->to(base_url('admin/users'))->with('success', 'Akun berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session()->get('id') == $id) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($id == 1) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Akun sistem utama tidak dapat dihapus.');
        }

        $db = \Config\Database::connect();
        $user = $db->table('tb_user')->where('id', $id)->get()->getRowArray();

        if (! $user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $db->table('tb_user')->where('id', $id)->delete();
        
        return redirect()->to(base_url('admin/users'))->with('success', 'Akun berhasil dihapus.');
    }

    public function toggle($id)
    {
        if (session()->get('id') == $id) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        if ($id == 1) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Akun sistem utama tidak dapat dinonaktifkan.');
        }

        $db = \Config\Database::connect();
        $user = $db->table('tb_user')->where('id', $id)->get()->getRowArray();

        if (! $user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $newStatus = ($user['is_active'] == 1) ? 0 : 1;
        $db->table('tb_user')->where('id', $id)->update(['is_active' => $newStatus]);

        $msg = $newStatus ? 'Akun diaktifkan.' : 'Akun dinonaktifkan.';
        return redirect()->to(base_url('admin/users'))->with('success', $msg);
    }
}
