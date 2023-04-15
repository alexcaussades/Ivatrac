<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class usersController extends Controller
{
    
    public function install_superadmin(Request $request)
    {
        $user = new users();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->remember_token = Str::random(10);
        $user->role = $request->role;
        $user->whitelist = $request->whitelist;
        $user->discord_users = $request->discordusers;
        $user->condition = $request->condition;
        $user->age = $request->age;
        $user->name_rp = $request->name_rp;
        $user->discord = $request->discord;
        $user->save();
    }
}
