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

$routes->get('admin/dashboard', 'Frontend::adminDashboard');
$routes->get('admin/menu', 'Frontend::adminMenu');
$routes->get('admin/menu/create', 'Frontend::adminMenuCreate');
$routes->get('admin/menu/edit/(:num)', 'Frontend::adminMenuEdit/$1');
$routes->get('admin/users', 'Frontend::adminUsers');
$routes->get('admin/riwayat', 'Frontend::adminRiwayat');
$routes->get('admin/riwayat-stok', 'Frontend::adminRiwayatStok');
$routes->get('admin/pengaturan', 'Frontend::adminPengaturan');

$routes->get('kasir/dashboard', 'Frontend::kasirDashboard');
$routes->get('kasir/pos', 'Frontend::kasirPos');
$routes->get('kasir/riwayat', 'Frontend::kasirRiwayat');
$routes->get('kasir/pengaturan', 'Frontend::kasirPengaturan');

$routes->get('welcome', 'Home::index');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
