<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutAdminController extends Controller
{
    protected $guard = "admin";

    // creation function in laravel activation session for admin via email adresse check

    public function check_admin(Request $request)
    {
        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            return Auth::guard('admin')->attempt(['email' => $request->email]);
        }
    }

    public function get_admin($id)
    {
        $admin = Admin::where('id', $id)->first();
        if ($admin) {
            return $admin;
        }
    }
}
