<?php

namespace App\Controllers;

class Frontend extends BaseController
{
    public function login() { return view('auth/login'); }
    public function adminDashboard() { return view('admin/dashboard'); }
    public function adminMenu() { return view('admin/menu/index'); }
    public function adminMenuCreate() { return view('admin/menu/form'); }
    public function adminMenuEdit($id) { return view('admin/menu/form'); }
    public function adminUsers() { return view('admin/users'); }
    public function adminRiwayat() { return view('admin/riwayat'); }
    public function adminRiwayatStok() { return view('admin/riwayat_stok/index'); }
    public function adminPengaturan() { return view('admin/pengaturan/index'); }
    public function kasirDashboard() { return view('kasir/pos'); }
    public function kasirPos() { return view('kasir/pos'); }
    public function kasirRiwayat() { return view('kasir/riwayat'); }
    public function kasirPengaturan() { return view('kasir/pengaturan'); }
}
