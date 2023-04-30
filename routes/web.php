<?php



use App\Models\whitelist;
use Illuminate\Support\Env;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\While_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtcController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\logginController;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\whitelistController;
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
        if(Auth::user() != null){
            return redirect()->route("serveur");
        }
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
            
            return view("serveur/index", ["users" => $users, "role" => $role, "whitelist" => $whitelist]);
        }
    })->name("serveur");

    Route::post("/", function (usersController $usersController, whitelistController $whitelistController, Request $request) {
        $whitelistController->create($request);
        $users = $usersController->get_info_user(auth()->user()->id);
        $role = $usersController->get_role_user(auth()->user()->role);
        $whitelist = $whitelistController->linkUser(auth()->user()->id);
        return view("serveur.index", ["users" => $users, "role" => $role, "whitelist" => $whitelist]);
    })->name("serveur.index");

    Route::get("slug", function () {
        $whitelistbdd = whitelist::where("id_users", auth()->user()->id)->first();
        $changename = $whitelistbdd->name_rp;
        $newname = Str::slug($changename);
        $whitelistbdd->slug = $newname;
        // mise a jour du slug
        $whitelistbdd->save();
        return redirect()->route("serveur.index");
    })->name("serveur.slug");
});


Route::get("logs", function (logginController $logginController) {
    if (!Auth::user()) {
        return redirect()->route("auth.login");
    } else {
        $logs = $logginController->getLoggins();
        return view("auth.logs", ["logs" => $logs]);
    }
})->middleware(["auth:admin"])->name("logs");

Route::get("logs-modo", function (logginController $logginController) {
    if (!Auth::user()) {
        return redirect()->route("auth.login");
    } else {
        $logs = $logginController->getLoggins();
        $loggin = new logginController();
        return view("auth.logs", ["logs" => $logs, "loggin" => $loggin]);
    }
})->middleware(["auth:modo"])->name("logs.modo");

Route::delete("logs/{id}", function (logginController $logginController, Request $request) {
    $request->merge([
        "id" => $request->id,
    ]);
    $logginController->deleteLogginForId($request->id);
    return redirect()->route("logs");
})->middleware(["auth:admin"])->name("logs.delete");

Route::delete("logs-modo/{id}", function (logginController $logginController, Request $request) {
    $request->merge([
        "id" => $request->id,
    ]);
    $logginController->deleteLogginForId($request->id);
    return redirect()->route("logs.modo");
})->middleware(["auth:modo"])->name("logs.modo.delete");