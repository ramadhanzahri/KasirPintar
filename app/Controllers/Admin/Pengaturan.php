<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Pengaturan extends BaseController
{
    protected SettingModel $settingModel;

    protected array $defaultSettings = [
        'store_name'        => 'Warung',
        'store_logo'        => '',
        'store_favicon'     => '',
        'store_description' => 'Sistem kasir pintar.',
    ];

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $this->ensureDefaultSettings();

        $settings = $this->settingModel->getAllSettings();

        return view('admin/pengaturan/index', [
            'title'    => 'Pengaturan Toko',
            'settings' => $settings,
        ]);
    }

    public function update()
    {
        $rules = [
            'store_name' => [
                'label' => 'Nama Toko',
                'rules' => 'required|max_length[100]',
            ],
            'store_description' => [
                'label' => 'Deskripsi Toko',
                'rules' => 'permit_empty|max_length[255]',
            ],
            'store_logo' => [
                'label' => 'Logo Toko',
                'rules' => 'permit_empty|is_image[store_logo]|mime_in[store_logo,image/jpg,image/jpeg,image/png,image/webp]|max_size[store_logo,2048]',
            ],
            'store_favicon' => [
                'label' => 'Favicon',
                'rules' => 'permit_empty|is_image[store_favicon]|mime_in[store_favicon,image/jpg,image/jpeg,image/png,image/webp]|max_size[store_favicon,1024]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->to(base_url('admin/pengaturan'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->settingModel->setValue('store_name', trim((string) $this->request->getPost('store_name')));
        $this->settingModel->setValue('store_description', trim((string) $this->request->getPost('store_description')));

        // Bersihkan sisa data lama dari database
        $this->settingModel->whereIn('setting_key', ['store_whatsapp', 'store_address', 'footer_text'])->delete();

        $logoPath = $this->uploadImage('store_logo', 'store_logo');

        if ($logoPath !== null) {
            $this->settingModel->setValue('store_logo', $logoPath);
        }

        $faviconPath = $this->uploadImage('store_favicon', 'store_favicon');

        if ($faviconPath !== null) {
            $this->settingModel->setValue('store_favicon', $faviconPath);
        }

        return redirect()
            ->to(base_url('admin/pengaturan'))
            ->with('success', 'Pengaturan toko berhasil diperbarui.');
    }

    private function ensureDefaultSettings(): void
    {
        foreach ($this->defaultSettings as $key => $value) {
            $exists = $this->settingModel
                ->where('setting_key', $key)
                ->first();

            if (! $exists) {
                $this->settingModel->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                ]);
            }
        }
    }

    private function uploadImage(string $inputName, string $settingKey): ?string
    {
        $file = $this->request->getFile($inputName);

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . 'uploads/settings';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = $inputName . '_' . time() . '_' . $file->getRandomName();

        $file->move($uploadPath, $newName);

        $newPath = 'uploads/settings/' . $newName;

        $oldPath = $this->settingModel->getValue($settingKey);

        if (! empty($oldPath)) {
            $fullOldPath = FCPATH . $oldPath;

            if (is_file($fullOldPath)) {
                @unlink($fullOldPath);
            }
        }

        return $newPath;
    }
}