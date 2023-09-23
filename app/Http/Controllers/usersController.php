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
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
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
        $user->name_first = $request->name_first;
        $user->name_last = $request->name_last;
        $user->vid = $request->vid;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = false;
        $user->remember_token = Str::random(10);
        $user->role = $request->role ? $request->role : 1;
        $user->condition = $request->condition ? $request->condition : 0;
        $user->age = $request->age ? $request->age : 0;
        $user->name_rp = $request->name_first . " " . $request->name_last;
        $user->name = $request->name_first . " " . $request->name_last;
        $user->save();
    }

    public function autentification(loginValidatorRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = users::where("id", auth()->user()->id)->first();
            Cookie::queue('email-Users', $user->email, time() + 60 * 60 * 24 * 30);
            if ($request->remember == "on") {
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

    public function connect_via_ivao($request, $data)
    {
        $user = users::where("vid", $data["id"])->first();
        //** generate session users */
        if ($user) {
            Auth::login($user);
            //$request->session()->regenerate();
            $user = users::where("id", auth()->user()->id)->first();
            //** Check if user is admin */
            $admin = new Admin();
            $check = $admin::where('email', $user->email)->first();
            if ($check) {
                Auth::guard('admin')->login($user);
            }
            $modo = new modo();
            $check = $modo::where('email', $user->email)->first();
            if ($check) {
                Auth::guard('modo')->login($user);
            }
            $logginController = new logginController();
            $logginController->infoLog("Connexion de " . $user->name . "", $user->id, $request->ip(), null);
            return redirect()->intended('home');
        } else {
            $request->merge([
                'vid' => $data["id"],
                'name_first' => $data["firstName"],
                'name_last' => $data["lastName"],
                'email' => $data["email"],
                'password' => Hash::make("ivao"),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'role' => 1,
                'condition' => 1,
                'age' => 1,
                'name_rp' => $data["firstName"] . " " . $data["lastName"],
                'name' => $data["firstName"] . " " . $data["lastName"],
            ]);
            $this->create($request);
            $user = users::where("vid", $data["id"])->first();
            Auth::login($user);
            $request->session()->regenerate();
            $user = users::where("id", auth()->user()->id)->first();
            //envoie mail for amdin
            $mailController = new MailRegisterController();
            $mailController->MailRegister($user->id);
            // return log for admin
            $logginController = new logginController();
            $logginController->infoLog("Connexion de " . $user->name . " ", $user->id, $request->ip(), null);
            return redirect()->intended('home');
        }
    }
    public function autentification_via_cookie()
    {
        $request = new Request();

        if (Cookie::get('email-Users') && Cookie::get('remember_token')) {

            if (Cookie::get('remember_token') == null) {
                Auth::logout();
                Cookie::queue(Cookie::forget('remember_token'));
                $request->session()->flush();
                return to_route("auth.login")->withErrors([
                    'error' => 'Les informations de connexion sont incorrectes. Veuillé vérifier votre adresse email et votre mot de passe.'
                ]);
            }
            $user = users::where("email", Cookie::get('email-Users'))->where("remember_token", Cookie::get('remember_token'))->first();
            if ($user) {
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
            }

            return redirect()->intended('serveur');
        }
    }

    public function logout(Request $request)
    {
        /** remove all session from the website */
        $request->session()->forget('email-Users');
        $request->session()->forget('remember_token');
        $request->session()->forget('ivao_tokens');
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

    public function get_all_users()
    {
        $users = users::all();
        return $users;
    }

    public function forget_password($request)
    {
        $user = users::where("email", $request->email)->first();
        $paswordNew = Str::password(10);
        $user->password = Hash::make($paswordNew);
        $user->save();
        $mailController = new MailRegisterController();
        $mailController->reset_password($user->email, $paswordNew);
        $logginController = new logginController();
        $logginController->infoLog("Demande de réinitialisation de mot de passe de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
        return $user;
    }

    public function verif_email($request)
    {
        /** Check if email via le token remenber */
        $user = users::where("remember_token", $request->token)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
            $logginController = new logginController();
            $logginController->infoLog("Vérification de l'email de " . $user->name . " (" . $user->email . ")", $user->id, $request->ip(), null);
            return $user;
        } else {
            return false;
        }
    }
}
