<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index()
    {
        return view('admin/users');
    }

    public function store()
    {
        // action store user
    }

    public function edit($id)
    {
        return view('admin/users_edit', ['id' => $id]);
    }

    public function update($id)
    {
        // action update user
    }

    public function delete($id)
    {
        // action delete user
    }

    public function toggle($id)
    {
        // action toggle status user
    }
}
