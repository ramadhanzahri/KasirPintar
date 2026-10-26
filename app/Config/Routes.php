<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');



/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
$routes->group('admin', ['filter' => 'role:superadmin'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Menu
    $routes->get('menu', 'Admin\Menu::index');
    $routes->get('menu/create', 'Admin\Menu::create');
    $routes->post('menu/store', 'Admin\Menu::store');
    $routes->get('menu/edit/(:num)', 'Admin\Menu::edit/$1');
    $routes->post('menu/update/(:num)', 'Admin\Menu::update/$1');
    $routes->post('menu/delete/(:num)', 'Admin\Menu::delete/$1');
    $routes->post('menu/toggle/(:num)', 'Admin\Menu::toggleStatus/$1');
    $routes->post('menu/toggle-stok/(:num)', 'Admin\Menu::toggleStok/$1');

    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->post('users/toggle/(:num)', 'Admin\Users::toggle/$1');

    // Riwayat
    $routes->get('riwayat', 'Admin\Riwayat::index');

    // Riwayat Stok
    $routes->get('riwayat-stok', 'Admin\RiwayatStok::index');
    $routes->post('riwayat-stok/store', 'Admin\RiwayatStok::store');
    $routes->post('riwayat-stok/update/(:num)', 'Admin\RiwayatStok::update/$1');
    $routes->post('riwayat-stok/delete/(:num)', 'Admin\RiwayatStok::delete/$1');

    // Pengaturan
    $routes->get('pengaturan', 'Admin\Pengaturan::index');
    $routes->post('pengaturan/update', 'Admin\Pengaturan::update');
    $routes->get('pengaturan/akun', 'Admin\Profil::index');
    $routes->post('pengaturan/akun/profile', 'Admin\Profil::updateProfile');
    $routes->post('pengaturan/akun/password', 'Admin\Profil::updatePassword');
});

/*
|--------------------------------------------------------------------------
| Kasir
|--------------------------------------------------------------------------
*/
$routes->group('kasir', ['filter' => 'role:kasir'], function ($routes) {

    // POS Pintar
    $routes->get('pos', 'Kasir\Pos::index');
    $routes->post('pos/submit', 'Kasir\Pos::submit');
    $routes->get('pos/stok', 'Kasir\Pos::stok');

    // Dashboard
    $routes->get('dashboard', 'Kasir\Dashboard::index');

    // Pembayaran tunai / COD / QRIS / Transfer
    $routes->post('telah-bayar/(:segment)', 'Kasir\Dashboard::telahBayar/$1');
    $routes->post('batal/(:segment)', 'Kasir\Dashboard::batal/$1');

    // Status pesanan
    $routes->post('update-status/(:segment)', 'Kasir\Dashboard::updateStatus/$1');


    // Live check
    $routes->get('cek_baru/(:segment)', 'Kasir\Dashboard::cek_baru/$1');
    $routes->get('cek_pembayaran_berhasil', 'Kasir\Dashboard::cek_pembayaran_berhasil');

    // Riwayat
    $routes->get('riwayat', 'Kasir\Riwayat::index');

    // Pengaturan
    $routes->get('pengaturan', 'Kasir\Pengaturan::index');
    $routes->post('pengaturan/profile', 'Kasir\Pengaturan::updateProfile');
    $routes->post('pengaturan/password', 'Kasir\Pengaturan::updatePassword');
});