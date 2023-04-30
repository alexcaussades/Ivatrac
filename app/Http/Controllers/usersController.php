<?php

namespace App\Http\Controllers;

use SNMP;
use App\Models\modo;
use App\Models\Admin;
use App\Models\roles;
use App\Models\users;
use App\Models\loggin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\logginController;
use App\Http\Requests\loginValidatorRequest;

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

    public function autentification(loginValidatorRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = users::where("id", auth()->user()->id)->first();
            //** Check if user is admin */
            $admin = new Admin();
            $check = $admin::where('email', $credentials['email'])->first();
            if($check){
                Auth::guard('admin')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']]);
            }
            $modo = new modo();
            $check = $modo::where('email', $credentials['email'])->first();
            if($check){
                Auth::guard('modo')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']]);
            }

            $logginController = new logginController();
            $logginController->infoLog("Connexion de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
            return redirect()->intended('serveur');
        }
        return to_route("auth.login")->withErrors([
            'error' => 'Les informations de connexion sont incorrectes. Veuillé vérifier votre adresse email et votre mot de passe.'
        ]);
    }

    public function logout(Request $request)
    {
        $user = users::where("id", auth()->user()->id)->first();
        $logginController = new logginController();
        $logginController->infoLog("Logout de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
        $request->session()->flush();
    }

    public function get_info_user($id)
    {
        $user = users::where("id", $id)->first();
        return $user;
    }

    public function get_role_user($id)
    {
        $roles = roles::where("id", $id)->first();
        return $roles;
    }

    public function get_email_user(Request $request)
    {
        $user = users::where("email", $request->email)->first();
        return $user->id;
    }

    public function loggin_form_register($id)
    {
        $user = users::where("id", $id)->first();
        $logginController = new logginController();
        $logginController->infoLog("inscription de " . $user->name . " (" . $user->email . ")", $user->id, null, null);
    }
}
