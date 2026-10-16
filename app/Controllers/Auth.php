<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            $path = $role === 'superadmin' ? 'admin/dashboard' : 'kasir/pos';
            return redirect()->to($path);
        }
        
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (! ($user['is_active'] ?? 1)) {
                $session->setFlashdata('msg', 'Akun Anda dinonaktifkan.');
                return redirect()->to('/auth');
            }

            if (password_verify($password, $user['password'])) {
                $session->set([
                    'id'           => $user['id'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'username'     => $user['username'],
                    'role'         => $user['role'],
                    'isLoggedIn'   => true,
                ]);
                $session->setFlashdata('msg', 'Login berhasil!');
                return redirect()->to($user['role'] === 'superadmin' ? 'admin/dashboard' : 'kasir/pos');
            }
            $session->setFlashdata('msg', 'Password salah.');
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan.');
        }
        return redirect()->to('/auth');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }
}