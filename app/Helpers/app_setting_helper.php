<?php

if (! function_exists('app_setting')) {
    function app_setting($key, $default = '') {
        $settings = [
            'store_name' => 'Nama Toko',
            'store_logo' => '',
            'store_favicon' => '',
            'store_description' => 'Sistem Kasir Pintar',
            'footer_text' => 'Powered by Kelompok 6'
        ];
        return $settings[$key] ?? $default;
    }
}
