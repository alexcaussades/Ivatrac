<?php



use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtcController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\metarController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\logginController;
use App\Http\Controllers\AutAdminController;
use App\Http\Controllers\PilotIvaoController;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\CreatAuhUniqueUsersController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MailRegisterController;
use App\Mail\MailTest;
use Illuminate\Support\Facades\Mail;

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
    return response()->view('welcome');
})->where('client', '[0-9]+');


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

    Route::post("register", function (Request $request) {
        if ($request->password == $request->password_confirmation) {
            if ($request->condition == "1") {

                $usersController = new usersController();
                $usersController->create($request);
                $lastId = DB::getPdo()->lastInsertId();
                $mail = new MailRegisterController();
                $mail->MailRegister($lastId);
                $mail->ConfirmRegister($lastId);
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

Route::prefix("serveur/")->group(function () {
    Route::get("/", function (usersController $usersController, Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $users = $usersController->get_info_user(auth()->user()->id);
            $role = $usersController->get_role_user(auth()->user()->role);


            return view("serveur/index", ["users" => $users, "role" => $role]);
        }
    })->name("serveur");

    Route::post("/", function (usersController $usersController, Request $request) {

        $users = $usersController->get_info_user(auth()->user()->id);
        $role = $usersController->get_role_user(auth()->user()->role);

        return view("serveur.index", ["users" => $users, "role" => $role]);
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

Route::prefix("metar")->group(function () {
    Route::get("/", function (Request $request) {
        return view("metar.index");
    })->name("metars.index");

    Route::get("/search", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $request->validate([
            "icao" => "required|size:4"
        ]);
        $icao = strtoupper($request->icao);
        $metarController = new metarController();
        $metar = $metarController->metar($icao);
        $taf = $metarController->taf($icao);
        $ATC = $metarController->getATC($icao);
        $chart = new ChartController();
        $chartIFR = $chart->chartIFR($icao);
        $chartVFR = $chart->chartVFR($icao);
        $pilots = new PilotIvaoController();
        $pilot = $pilots->getAirplaneToPilots($icao);
        
        if($metar == NULL || $taf == NULL || $ATC == NULL || $pilot == NULL || $chartIFR == NULL || $chartVFR == NULL){
            return view("metar.reload", ["icao" => $icao]);
        }
       
        return view("metar.icao", ["metar" => $metar, "taf" => $taf, "ATC" => $ATC, "pilot" => $pilot, "chartIFR" => $chartIFR, "chartVFR" => $chartVFR]);
    })->name("metars.icao");

    Route::get("/{icao}", function () {
        return to_route("metars.index");
    });

});

Route::prefix("ivao")->group(function () {

    Route::get("/", function (Request $request) {
        return to_route("metars.index");
    });

    Route::get("/info", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $request->validate([
            "icao" => "required|size:4"
        ]);
        $icao = strtoupper($request->icao);
        $ivaoController = new metarController();
        $atcivao = new AtcController();
        $pilots = new PilotIvaoController();
        $ivao = $ivaoController->getATC($icao);
        $atc = $atcivao->resolve($ivao);
        $Pilot = $pilots->getAirplaneToPilots($icao);
        return [
            "ATC" => $atc,
            "Pilot" => $Pilot,
            "ivao" => $ivao,
        ];
    })->name("ivao.info");

    Route::get("/plateforme", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $request->validate([
            "icao" => "required|size:4"
        ]);
        $icao = strtoupper($request->icao);
        $ivaoController = new metarController();
        $atcivao = new AtcController();
        $pilots = new PilotIvaoController();
        $ivao = $ivaoController->getATC($icao);
        $atc = $atcivao->resolve($ivao);
        $Pilot = $pilots->getAirplaneToPilots($icao);
        return view("plateforme.plat", ["ATC" => $atc, "Pilot" => $Pilot, "ivao" => $ivao]);
    })->name("ivao.plateforme");

    Route::get("/pilot", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $pilots = new PilotIvaoController();
        $response = $pilots->getAirplaneToPilots($request->icao);
        return $response;
    });
});

Route::get("/mail", function (mailController $mailTest, usersController $usersController, Request $request) {

    $mailTest->basic_email(auth()->user()->email);

    return "Email sent successfully";
});

Route::get('/test', function () {
    $usersController = new usersController();
    $user = $usersController->get_info_user(1);
    return view('emails.registerUsers.confirm-register', ["user" => $user]);
});
