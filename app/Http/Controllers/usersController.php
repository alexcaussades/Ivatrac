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
use Illuminate\Support\Facades\Cookie;

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
            Cookie::queue('email-Users', $user->email, time() + 60 * 60 * 24 * 30);
            if ($request->remember == "on"){
                Cookie::queue('remember_token', $user->remember_token, time() + 86400 * 30);
            }
            //** Check if user is admin */
            $admin = new Admin();
            $check = $admin::where('email', $credentials['email'])->first();
            if ($check) {
                Auth::guard('admin')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']]);
            }
            $modo = new modo();
            $check = $modo::where('email', $credentials['email'])->first();
            if ($check) {
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

    public function autentification_via_cookie(){
        $request = new Request();
        
        if( Cookie::get('email-Users') && Cookie::get('remember_token')){
           
            if (Cookie::get('remember_token') == null) {
                Auth::logout();
                Cookie::queue(Cookie::forget('remember_token'));
                $request->session()->flush();
                return to_route("auth.login")->withErrors([
                    'error' => 'Les informations de connexion sont incorrectes. Veuillé vérifier votre adresse email et votre mot de passe.'
                ]);
            }          
                $user = users::where("email", Cookie::get('email-Users'))->where("remember_token", Cookie::get('remember_token'))->first();
                Auth::loginUsingId($user->id);
                //** Check if user is admin */
                $admin = new Admin();
                $check = $admin::where('email', $user->email)->first();
                if ($check) {
                    Auth::guard('admin')->login($user);
                }
                $modo = new modo();
                $check = $modo::where('email', Cookie::get('email-Users'))->first();
                if ($check) {
                    Auth::guard('modo')->login($user);
                }
    
                $logginController = new logginController();
                $logginController->infoLog("Automatique Session de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
                return redirect()->intended('serveur');
            
        }
        return to_route("auth.login")->withErrors([
            'error' => 'Les informations de connexion sont incorrectes. "Code erreur 1".'
        ]);
    }

    public function logout(Request $request)
    {
        $user = users::where("id", auth()->user()->id)->first();
        $logginController = new logginController();
        $logginController->infoLog("Logout de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
        Auth::logout();
        Cookie::queue(Cookie::forget('remember_token'));
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
