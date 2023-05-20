<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function new()
    {
        return view("roles.new");
    }

    public function create($name, $information)
    {
        $roles = new \App\Models\roles();
        $roles->create_Roles($name, $information);
        return $roles;
    }

    public function get_all()
    {
        $roles = new \App\Models\roles();
        return $roles->get_all();
    }

    public function get($id)
    {
        $roles = new \App\Models\roles();
        return $roles->get($id);
    }
}
