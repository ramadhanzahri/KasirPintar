<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;

class Pengaturan extends BaseController
{
    public function index()
    {
        return view('kasir/pengaturan');
    }

    public function updateProfile()
    {
        // action update profile
    }

    public function updatePassword()
    {
        // action update password
    }
}
