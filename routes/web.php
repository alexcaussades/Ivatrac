<?php


use Illuminate\Support\Env;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\While_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtcController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\DiscordNotfyController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\CreatAuhUniqueUsersController;

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

Route::get('/', function (Request $request) {
    $auth = "";
    if ($request->input("client") == "123456789") {
        $auth = "autentification reussie";
    };
    return [
        'name' => 'John Doe',
        'age' => 30,
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'all' => $request->input("all", "je suis un parametre par defaut"),
        'auth' => $auth

    ];
})->where('client', '[0-9]+');


Route::get('discord', function (DiscordNotfyController $discordNotfyController) {
    $discordNotfyController->newAddWhiteList("Ludovic Ramirez", "Legolas#5525", "Ludovic@gmail.com");
    return redirect()->route("welcome");
});

Route::get("/whitelist", function (Request $request) {
    return view("whitelist");
})->name("whitelist");

Route::prefix("atc/")->group(function () {
    Route::get('/', function (AtcController $atcController) {
        return $atcController->new();
    });

    Route::get('add/{vid}', function (AtcController $atcController, Request $request) {

        $request->merge([
            "vid" => $request->vid
        ]);

        $essais_bdd = new \App\Models\Essais();
        $essais_bdd->name = $request->vid;
        $essais_bdd->save();

        return redirect("atc/view");
    });

    Route::get('view/{id}', function (AtcController $atcController, Request $request) {
        $essais_bdd = \App\Models\Essais::find($request->id);
        return $essais_bdd;
    });
    Route::get('view', function (AtcController $atcController) {
        $essais_bdd = \App\Models\Essais::all(["id", "name"]);
        return $essais_bdd;
    })->name("atc.view");
    Route::get('delect/{id}', function (AtcController $atcController, Request $request) {
        $essais_bdd = \App\Models\Essais::find($request->id);
        $essais_bdd->delete();
        return redirect()->route("atc.view");
    });
});

Route::prefix("auth/")->group(function () {
    Route::get("add", [CreatAuhUniqueUsersController::class, "creatAuthUniqueUses"]);
    Route::get("verif/{id}", function (Request $request, CreatAuhUniqueUsersController $creatAuhUniqueUsersController) {
        return $creatAuhUniqueUsersController->verifid($request);
    });
    Route::get("delete", [CreatAuhUniqueUsersController::class, "deleteUID"]);
    Route::get("login", function () {
        return view("auth.login");
    })->name("auth.login");

    Route::post("login", function (Request $request, usersController $usersController) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $usersController->login($request);

        if ($validator->fails()) {
            return redirect()->route("auth.login")
                ->withErrors($validator)
                ->withInput();
        }
        return redirect("serveur");
    });

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
    Route::get("/", function (usersController $usersController) {
        if(session()->get("id") == null){
            return redirect("auth/login");
        }
        $users = $usersController->get_info_user(session()->get("id"));
        $role = $usersController->get_role_user(session()->get("role"));
        return view("serveur.index", ["users" => $users, "role" => $role]);
    })->name("serveur.index");
});
