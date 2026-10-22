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

$routes->group('admin', ['filter' => 'role:superadmin'], static function ($routes) {
    $routes->get('dashboard', 'Frontend::adminDashboard');
    $routes->get('menu', 'Frontend::adminMenu');
    $routes->get('menu/create', 'Frontend::adminMenuCreate');
    $routes->get('menu/edit/(:num)', 'Frontend::adminMenuEdit/$1');
    $routes->get('users', 'Frontend::adminUsers');
    $routes->get('riwayat', 'Frontend::adminRiwayat');
    $routes->get('riwayat-stok', 'Frontend::adminRiwayatStok');
    $routes->get('pengaturan', 'Frontend::adminPengaturan');
});

$routes->group('kasir', ['filter' => 'role:kasir'], static function ($routes) {
    $routes->get('dashboard', 'Frontend::kasirDashboard');
    $routes->get('pos', 'Frontend::kasirPos');
    $routes->get('riwayat', 'Frontend::kasirRiwayat');
    $routes->get('pengaturan', 'Frontend::kasirPengaturan');
});

$routes->get('welcome', 'Home::index');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
