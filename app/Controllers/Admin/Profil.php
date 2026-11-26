<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('id'));

        return view('admin/profil', [
            'title' => 'Pengaturan Akun',
            'user'  => $user,
        ]);
    }

    public function updateProfile()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'username'     => 'required|min_length[3]|max_length[50]|alpha_dash',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        $userModel = new UserModel();
        $username = trim((string) $this->request->getPost('username'));

        $existing = $userModel->where('username', $username)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        $nama = trim((string) $this->request->getPost('nama_lengkap'));
        $userModel->update($userId, [
            'nama_lengkap' => $nama,
            'username'     => $username,
        ]);

        session()->set([
            'nama_lengkap' => $nama,
            'username'     => $username,
        ]);

        return redirect()->to(base_url('admin/pengaturan/akun'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $rules = [
            'old_password'     => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $oldPassword = (string) $this->request->getPost('old_password');
        if (! $user || ! password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password lama tidak sesuai.');
        }

        $newPassword = (string) $this->request->getPost('new_password');
        $userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('admin/pengaturan/akun'))->with('success', 'Password berhasil diperbarui.');
    }
}
