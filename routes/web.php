<?php



use App\Models\whitelist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\logginController;
use App\Http\Controllers\AutAdminController;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Controllers\DiscordNotfyController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\CreatAuhUniqueUsersController;
use App\Http\Controllers\metarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function (usersController $usersController, Request $request, Session $session) {
    $users = $usersController->get_info_user(session()->get("id"));
    return view('welcome', ["users" => $users]);
})->name("welcome");

Route::get('/login', function (Request $request) {
    $authuser = new usersController();
    $authuser->autentification_via_cookie();
    return to_route("auth.login");
})->name("login");

Route::get('/logout', function (Request $request) {
    return to_route("auth.logout");
})->name("logout");

Route::get('/', function (Request $request) { 
    /** creation d'un cookie sur laravel */
    
    $authuser = new usersController();
    $authuser->autentification_via_cookie($request);       
    return response()->view('welcome')->cookie('name', 'value', 0.5);
})->where('client', '[0-9]+');

Route::get('discord', function (DiscordNotfyController $discordNotfyController) {
    $discordNotfyController->newAddWhiteList("Ludovic Ramirez", "Legolas#5525", "Ludovic@gmail.com");
    return redirect()->route("welcome");
});

Route::get("/whitelist", function (Request $request) {
    $accounts = whitelist::all();
    return view("whitelist", ["accounts" => $accounts]);
})->name("whitelist");

Route::get("/whitelist/{slug}", function (Request $request, whitelistController $whitelistController) {
    $request->merge([
        "slug" => $request->slug
    ]);

    $whitelistController = $whitelistController->view($request);
    if ($whitelistController == null) {
        return redirect()->route("whitelist");
    }
    return view("whitelist-name", ["slug" => $whitelistController]);
})->name("whitelist.slug");

Route::prefix("gestion-white/")->group(function () {
    Route::get("/", function (Request $request, whitelistController $whitelistController) { 
        $whitelistController = $whitelistController->viewAll();     
        return view("serveur.whitelist.whitelistview", ["whitelist" => $whitelistController]);
    })->name("whitelist-admin")->middleware("auth");

    Route::get("/check/{slug}", function (Request $request, whitelistController $whitelistController) {
        $request->merge([
            "slug" => $request->slug
        ]);

        $whitelistController = $whitelistController->view($request);
        $users = new usersController();
        $users = $users->get_info_user($whitelistController->id_users);
        if ($whitelistController == null) {
            return redirect()->route("whitelist");
        }
        return view("serveur.whitelist.whitelistcheck", ["slug" => $whitelistController, "users" => $users]);
    })->name("whitelist-admin.check")->middleware("auth");

    Route::post("/check/{slug}", function (Request $request, whitelistController $whitelistController) {
        $request->merge([
            "slug" => $request->slug
        ]);

        $whitelistController = $whitelistController->check($request);
        if ($whitelistController == null) {
            return redirect()->route("whitelist");
        }
        return redirect()->route("whitelist-admin", ["slug" => $whitelistController]);
    })->name("whitelist-admin.check")->middleware("auth");

    Route::get("/edit/{slug}", function (Request $request, whitelistController $whitelistController) {
        $request->merge([
            "slug" => $request->slug
        ]);

        $whitelistController = $whitelistController->view($request);
        $users = new usersController();
        $users = $users->get_info_user($whitelistController->id_users);
        if ($whitelistController == null) {
            return redirect()->route("whitelist");
        }
        return view("serveur.whitelist.whitelistupdate", ["slug" => $whitelistController, "users" => $users]);
    })->name("whitelist-admin.edit")->middleware("auth");

    // TODO: faire la route pour l'update de la whitelist sur une page externe @alexcaussades #9
    Route::post("/edit/{slug}", function (Request $request, whitelistController $whitelistController) {
        $request->merge([
            "slug" => $request->slug
        ]);

        $whitelistController = $whitelistController->edit($request);
        if ($whitelistController == null) {
            return redirect()->route("whitelist");
        }
        return redirect()->route("whitelist-admin", ["slug" => $whitelistController]);
    })->name("whitelist-admin.edit")->middleware("auth");

    Route::get("/delete/{slug}", function (Request $request, whitelistController $whitelistController) {
        $request->merge([
            "slug" => $request->slug
        ]);

        $whitelistController = $whitelistController->delete($request);
        if ($whitelistController == null) {
            return redirect()->route("whitelist");
        }
        return redirect()->route("whitelist");
    })->name("whitelist-admin.delete")->middleware("admin");

    Route::post("/add-serveur/{id}", function (Request $request, whitelistController $whitelistController, usersController $usersController) {
        $request->merge([
            "id" => $request->id
        ]);
        
        if ($whitelistController == null) {
            return redirect()->route("serveur");
        }
        $usercontroller = new whitelistController();
        $usercontroller->update_users_whitelist($request->id, "3");
        return redirect()->route("whitelist-admin");
    })->name("whitelist-admin-add-serveur")->middleware("auth");

    Route::post("/refus-serveur/{id}", function (Request $request, whitelistController $whitelistController, usersController $usersController) {
        $request->merge([
            "id" => $request->id
        ]);
        
        if ($whitelistController == null) {
            return redirect()->route("serveur");
        }
        $usercontroller = new whitelistController();
        $usercontroller->update_users_whitelist($request->id, "2");
        return redirect()->route("whitelist-admin");
    })->name("whitelist-admin-refus-serveur")->middleware("auth");
});

Route::prefix("auth/")->group(function () {
    Route::get("add", [CreatAuhUniqueUsersController::class, "creatAuthUniqueUses"]);
    Route::get("verif/{id}", function (Request $request, CreatAuhUniqueUsersController $creatAuhUniqueUsersController) {
        return $creatAuhUniqueUsersController->verifid($request);
    });
    Route::get("delete", [CreatAuhUniqueUsersController::class, "deleteUID"]);
    Route::get("login", function () {
        if (Auth::user() != null) {
            return redirect()->route("serveur");
        }
        $authuser = new usersController();
        $authuser->autentification_via_cookie();   
        return view("auth.login");
    })->name("auth.login");

    Route::post("login", [\App\Http\Controllers\usersController::class, "autentification"]);

    Route::get("register", function () {

        return view("auth.register");
    })->name("auth.register");

    Route::post("register", function (registerValidationRequest $request) {

        if ($request->password == $request->password_confirmation) {
            if ($request->condition == "1") {

                $validator = Validator::make($request->all(), [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'discordusers' => ['required', 'string', 'min:3', 'max:255', 'unique:users', 'regex:/^([a-zA-Z0-9]+)#([0-9]{4})$/'],
                ]);
                if ($validator->fails()) {
                    return redirect()->route("auth.register")
                        ->withErrors($validator)
                        ->withInput();
                }

                $usersController = new usersController();
                $usersController->create($request);
                $lastId = DB::getPdo()->lastInsertId();
                $usersController->loggin_form_register($lastId);
                return redirect()->route("auth.login");
            } else {
                return redirect()->route("auth.register")
                    ->withErrors("Vous devez accepter la CGU")
                    ->withInput();
            }
        } else {
            return redirect()->route("auth.register")
                ->withErrors("Les mots de passe ne sont pas identique !")
                ->withInput();
        }
        return view("auth.login");
    });

    Route::get("logout", function (usersController $usersController, Request $request) {
        $usersController->logout($request);
        return redirect()->route("auth.login");
    })->name("auth.logout");
});

Route::prefix("install/")->group(function () {
    Route::get("roles", function (RolesController $rolesController) {
        $rolesController->create("register", "en attente de validation de sont compte");
        $rolesController->create("user", "utilisateur sans whitelist");
        $rolesController->create("whitelist", "utilisateur avec whitelist");
        $rolesController->create("moderator_groupe_whitelist", "moderateur de sont groupe de whitelist");
        $rolesController->create("administrateur_groupe_whitelist", "administrateur de sont groupe de la whitelist");
        $rolesController->create("moderator_whitelist", "moderateur de la whitelist");
        $rolesController->create("administrateur_whitelist", "administrateur de la whitelist");
        $rolesController->create("staff", "Staff de la whitelist");
        $rolesController->create("administrateur", "administrateur du site");
        $rolesController->create("super_administrateur", "super administrateur du site");
        $roles_get = \App\Models\Roles::all();
        return  $roles_get;
    });

    Route::get("roles/{id}", function (RolesController $rolesController, Request $request) {
        $roles_get = \App\Models\Roles::find($request->id);
        return  $roles_get;
    });

    Route::get("/", function () {
        return [
            "roles" => "install/roles",
            "users" => "install/users",
            "whitelist" => "install/whitelist",
            "discord" => "install/discord",
            "atc" => "install/atc",
            "auth" => "install/auth",
        ];
    });

    Route::get("admin", function (usersController $users_websiteController, Request $request) {
        $request->merge([
            "name" => "Alexandre Caussades",
            "email" => "alexcaussades@gmail.com",
            "password" => Env("MY_PASS_APP"),
            "role" => "10",
            "whitelist" => "1",
            "discordusers" => "Legolas#5525",
            "condition" => "1",
            "age" => "1",
            "discord" => "1",
            "name_rp" => "Darius Lambert",
        ]);
        $users_websiteController->install_superadmin($request);
        $users_websiteController = \App\Models\users::all();
        return $users_websiteController;
    });
});

Route::prefix("serveur/")->group(function () {
    Route::get("/", function (usersController $usersController, whitelistController $whitelistController, Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $users = $usersController->get_info_user(auth()->user()->id);
            $role = $usersController->get_role_user(auth()->user()->role);
            $whitelist = $whitelistController->linkUser(auth()->user()->id);
            $whitelistAttente = $whitelistController->count_whitelist_attente();

            return view("serveur/index", ["users" => $users, "role" => $role, "whitelist" => $whitelist, "whitelistAttente" => $whitelistAttente]);
        }
    })->name("serveur");

    Route::post("/", function (usersController $usersController, whitelistController $whitelistController, Request $request) {
        $whitelistController->create($request);
        $users = $usersController->get_info_user(auth()->user()->id);
        $role = $usersController->get_role_user(auth()->user()->role);
        $whitelist = $whitelistController->linkUser(auth()->user()->id);
        return view("serveur.index", ["users" => $users, "role" => $role, "whitelist" => $whitelist]);
    })->name("serveur.index");

    Route::get("api", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $api = new ApiGestionController();
            $information = $api->check_Informations(Auth::user()->id);
            return view("serveur.api", ["information" => $information]);
        }
    })->name("serveur.api");

    Route::post("api", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $api = new ApiGestionController();
            $information = $api->creat_keys_api();
            /** Faire une function de masquage */

            return view("serveur.api", ["information" => $information]);
        }
    })->name("serveur.api.post");

    Route::post("api/create", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $api = new ApiGestionController();
            $api->creat_keys_api();
            return to_route("serveur.api");
        }
    })->name("serveur.api.create");

    Route::post("api/delete", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $api = new ApiGestionController();
            $api->delete_keys_api($request);
            return to_route("serveur.api");
        }
    })->name("serveur.api.delete");

    Route::get("api/documentation", function (Request $request) {
        /** verification des buttons d'action serveur */
        return url("https://github.com/alexcaussades/L10/wiki/API");
    })->name("serveur.api.documentation");
});

Route::prefix("logs")->group(function () {
    Route::get("/", function (logginController $logginController) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $logs = $logginController->getLoggins();
            return view("auth.logs", ["logs" => $logs]);
        }
    })->middleware(["auth:admin"])->name("logs");

    Route::get("modo", function (logginController $logginController) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $logs = $logginController->getLoggins();
            return view("auth.logs", ["logs" => $logs]);
        }
    })->middleware(["auth:modo"])->name("logs.modo");

    Route::get("{id}", function (logginController $logginController, Request $request) {
        $request->merge([
            "id" => $request->id
        ]);
        $logs = $logginController->getLoggin($request->id);
        $users = new usersController();
        $users = $users->get_info_user($logs->user);
        $admin = new AutAdminController();
        $admin = $admin->get_admin($logs->users_admin_id);
        
        return [
            "logs" => [
                "id" => $logs->id ?? Null,
                "user" => $logs->user ?? Null,
                "users_admin_id" => $logs->users_admin_id ?? Null,
                "action" => $logs->message,
                "ip" => $logs->ip ?? Null,
                "created_at" => date("d/m/Y H:i:s", strtotime($logs->created_at)) ?? Null,
            ],
            "user" => [
                "name" => $users->name ?? Null,
                "email" => $users->email ?? Null,
                "discord" => $users->discord ?? Null,
                "discordusers" => $users->discord_users ?? Null,
                "role" => $users->role ?? Null,
                "whiteList" => $users->whiteList ?? Null,
                "name_rp" => $users->name_rp ?? Null,
            ],
            "admin" => [
                "id" => $admin->id ?? Null,
                "users_id" => $admin->users_id ?? Null,
                "E-mail" => $admin->email ?? Null
            ]

            ];
    })->middleware(["auth:admin"])->name("logs.modo.id");

    Route::delete("{id}", function (logginController $logginController, Request $request) {
        $logginController->delete($request);
        return redirect()->route("logs");
    })->middleware(["auth:admin"])->name("logs.delete");

    Route::delete("modo/{id}", function (logginController $logginController, Request $request) {
        $logginController->delete($request);
        return redirect()->route("logs.modo");
    })->middleware(["auth:modo"])->name("logs.modo.delete");
});

Route::prefix("install/")->group(function () {
    Route::get("roles", function (RolesController $rolesController) {
        $rolesController->create("register", "en attente de validation de sont compte");
        $rolesController->create("user", "utilisateur sans whitelist");
        $rolesController->create("whitelist", "utilisateur avec whitelist");
        $rolesController->create("moderator_groupe_whitelist", "moderateur de sont groupe de whitelist");
        $rolesController->create("administrateur_groupe_whitelist", "administrateur de sont groupe de la whitelist");
        $rolesController->create("moderator_whitelist", "moderateur de la whitelist");
        $rolesController->create("administrateur_whitelist", "administrateur de la whitelist");
        $rolesController->create("staff", "Staff de la whitelist");
        $rolesController->create("administrateur", "administrateur du site");
        $rolesController->create("super_administrateur", "super administrateur du site");
        $roles_get = \App\Models\Roles::all();
        return  $roles_get;
    });

    Route::get("roles/{id}", function (RolesController $rolesController, Request $request) {
        $roles_get = \App\Models\Roles::find($request->id);
        return  $roles_get;
    });

    Route::get("/", function () {
        return [
            "roles" => "install/roles",
            "users" => "install/users",
            "whitelist" => "install/whitelist",
            "discord" => "install/discord",
            "atc" => "install/atc",
            "auth" => "install/auth",
        ];
    });

    Route::get("admin", function (usersController $users_websiteController, Request $request) {
        $request->merge([
            "name" => "Alexandre Caussades",
            "email" => "alexcaussades@gmail.com",
            "password" => Env("MY_PASS_APP"),
            "role" => "10",
            "whitelist" => "1",
            "discordusers" => "Legolas#5525",
            "condition" => "1",
            "age" => "1",
            "discord" => "1",
            "name_rp" => "Darius Lambert",
        ]);
        $users_websiteController->install_superadmin($request);
        $users_websiteController = \App\Models\users::all();
        return $users_websiteController;
    });
});

Route::get('/test', function (Request $request) {
    
    if($request->bearerToken()=="123456789"){
        return "autentification reussie";
    }else{
        return "autentification echoué";
    }
    
});

Route::get("metar", function (Request $request) {
    $metar = new metarController();
    $metar = $metar->sercretMetarGitHub();
    return $metar;
});