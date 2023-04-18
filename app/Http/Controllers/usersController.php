<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\users;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public function create(Request $request)
    {
        $user = new users();
        $user->name = $request->name_rp;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->remember_token = Str::random(10);
        $user->role = $request->role ? $request->role : 1;
        $user->whitelist = $request->whiteList ? $request->whiteList : 0;
        $user->discord_users = $request->discordusers ? $request->discordusers : 0;
        $user->condition = $request->condition ? $request->condition : 0;
        $user->age = $request->age ? $request->age : 0;
        $user->name_rp = $request->name_rp;
        $user->discord = $request->discord;
        $user->save();
    }

    public function login(Request $request)
    {
        $user = users::where("email", $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put("user", $user);
                $request->session()->put("id", $user->id);
                $request->session()->put("role", $user->role);
                $request->session()->put("whitelist", $user->whiteList);
                $request->session()->put("discord_users", $user->discord_users);
                return view("serveur.index");
            } else {
                return view("auth.login");
            }
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
    }

    public function get_info_user()
    {
        $user = users::where("id", session()->get("id"))->first();
        return $user;
    }

    public function get_role_user()
    {
        $roles = roles::where("id", session()->get("role"))->first();
        return $roles;
    }
}
