<?php



use App\Mail\MailTest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Sleep;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Monolog\Formatter\JsonFormatter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtcController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\metarController;
use App\Http\Controllers\PirepController;
use App\Http\Controllers\temsiController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DiscordContoller;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\logginController;
use App\Http\Controllers\testingContolleur;
use App\Http\Controllers\whazzupController;
use App\Http\Controllers\AutAdminController;
use App\Http\Controllers\PilotIvaoController;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Controllers\AuthIVAOController;
use App\Http\Controllers\chartIvaoFRcontroller;
use App\Http\Controllers\frendly_userController;
use App\Http\Controllers\MailRegisterController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\myOnlineServeurController;
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
    $whazzup = new whazzupController();
    $whazzup = $whazzup->connexion();
    dd($whazzup);
    return view('welcome', ["users" => $users, "whazzup" => $whazzup]);
})->name("welcome");

Route::get('/login', function (Request $request) {
    $authuser = new usersController();
    $authuser->autentification_via_cookie();
    return to_route("auth.login");
})->name("login");

Route::get('/logout', function (Request $request) {
    return to_route("auth.logout");
})->name("logout");

Route::get('/', function (Request $request, usersController $usersController) {
    /** creation d'un cookie sur laravel */
    $users = $usersController->autentification_via_cookie();
    $whazzup = new whazzupController();
    $whazzup->getwhazzup();
    $whazzup = $whazzup->connexion();
    $bddid = new whazzupController();
    $idlast = $bddid->bddid();

    $heurechange = $bddid->heurechange();
    return response()->view('welcome', ["whazzup" => $whazzup, "idlast" => $idlast, "heurechange" => $heurechange]);
})->where('client', '[0-9]+')->name("home");

Route::get("callback", function (Request $request) {
    $request->merge([
        "code" => $request->code
    ]);
    dd($request);
})->name("callback");


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

    Route::get("forget-password", function () {
        return view("auth.forget");
    })->name("auth.forget");

    Route::get("register", function () {

        return view("auth.register");
    })->name("auth.register");

    Route::post("register", function (Request $request) {
        if ($request->password == $request->password_confirmation) {
            if ($request->condition == "1") {

                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                ]);
                if ($validator->fails()) {
                    return redirect()->route("auth.register")
                        ->withErrors("L'email est déjà utilisé ! ")
                        ->withInput();
                }
                $password = Str::password();
                $request->merge([
                    "password" => $password,
                ]);
                $usersController = new usersController();
                $usersController->create($request);
                $lastId = DB::getPdo()->lastInsertId();
                $mail = new MailRegisterController();
                $mail->ConfirmRegister($lastId, $password);
                $mail->MailRegister($lastId);
                $mail->verrify_email($lastId);
                return redirect()->route("auth.login");
            } else {
                return redirect()->route("auth.register")
                    ->withErrors("Vous devez accepter la CGU")
                    ->withInput();
            }
        }
        return view("auth.login");
    });

    Route::post("forget-password", function (Request $request) {

        $usersController = new usersController();
        $usersController->forget_password($request);
        return redirect()->route("auth.login");
    })->name("auth.forget-password");

    Route::get('verif-email/{token}', function (Request $request, usersController $usersController) {
        $usersController->verif_email($request);
        return redirect()->route("auth.login");
    })->name('auth.verif-email');

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

    Route::get("security", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            return view("serveur.security.index");
        }
    })->name("serveur.secrity");

    Route::get("security/add", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $users = new usersController();
            $all = $users->get_all_users();
            return view("serveur.security.administrator-moderator", ["users" => $all]);
        }
    })->name("serveur.secrity.add");
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
        $temsi = new temsiController();
        $temsi = $temsi->all_chart();
        return view("metar.index", ["temsi" => $temsi]);
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
        $wazzup = new whazzupController();
        $metar = $metarController->metar($icao);
        $taf = $metarController->taf($icao);
        $ATC = $wazzup->ckeck_online_atc($icao);
        // Probleme de requete des cartes IFR et VFR    
        $pilots = new PilotIvaoController();
        $pilot = $pilots->getAirplaneToPilots($icao);

        if ($metar == NULL || $taf == NULL || $ATC == NULL || $pilot == NULL) {
            return view("metar.reload", ["icao" => $icao]);
        }

        return view("metar.icao", ["metar" => $metar, "taf" => $taf, "atc" => $ATC, "pilot" => $pilot]);
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
        $whazzup = new whazzupController();
        $atc = $atcivao->getRwy($request->icao);
        $ivao = $whazzup->ckeck_online_atc($request->icao);
        $Pilot = $pilots->getAirplaneToPilots($icao);
        $other = $ivaoController->getFirAtc($icao);
        $other2 = $ivaoController->getFirCTR($icao);

        $hosturl = $request->fullUrl();
        return view("plateforme.plat", ["atc" => $atc, "Pilot" => $Pilot, "ivao" => $ivao, "hosturl" => $hosturl, "other" => $other, "other2" => $other2]);
    })->name("ivao.plateforme");

    Route::get("/pilot", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $pilots = new PilotIvaoController();
        $response = $pilots->getAirplaneToPilots($request->icao);
        return $response;
    });

    Route::get("/bookings", function (Request $request){
        $whazzup = new whazzupController();
        $bookings = $whazzup->Bookings();
        $date = date("d/m/Y");
        return view("ivao.bookings", ["bookings" => $bookings, "date" => $date]);
    })->name("ivao.bookings")->middleware(["auth:web"]);
});

Route::prefix("pirep")->group(function () {
    Route::get("/", function (Request $request) {
        return view("pirep.index");
    })->name("pirep.index");

    Route::get("/create", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            return view("pirep.create");
        }
    })->name("pirep.create");

    Route::post("/create", function (Request $request) {
        $value = $request->all();
        $pirep = new PirepController();
        $pirep->create_for_website($value);
        return redirect()->route("pirep.index");
    })->name("pirep.create");

    Route::get("/upload", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            return view("pirep.upload-fpl");
        }
    })->name("pirep.upload");

    Route::post("/upload", function (Request $request) {
        $request->validate([
            "fpl" => "required"
        ]);
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $pirep = new PirepController();
            $pirep->store_fpl($request);
            return redirect()->route("pirep.index");
        }
    })->name("pirep.upload");

    Route::get("/show", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $pirep = new PirepController();
            $oo = $pirep->show_fpl_id(4);

            $json = json_decode($oo->fpl);
            //dd($oo);
            return view("pirep.show", ["json" => $json, "oo" => $oo]);
        }
    })->name("pirep.show");

    Route::get("/all", function (Request $request) {
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {
            $pirep = new PirepController();
            $oo = $pirep->show_fpl_user(auth()->user()->id);
            $json = json_decode($oo);
            return view("fpl.index", ["json" => $json]);
        }
    })->name("pirep.all");
});


Route::prefix("donwloader")->group(function () {
    Route::get("secure_auth", function (Request $request) {
        $request->merge([
            "id" => $request->id
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->route("auth.register")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
    })->name("download.auth");

    Route::get("tempsi", function (Request $request) {
        $temsi = new temsiController();
        $temsi = $temsi->get_temsi($request->id);
        return $temsi;
    })->name("download.tempsi")->middleware(["auth:web"]);

    Route::get("wintemp", function (Request $request) {
        $temsi = new temsiController();
        $temsi = $temsi->get_wintemp($request->id);
        return $temsi;
    })->name("download.wintemp")->middleware(["auth:web"]);
});

Route::prefix("friends")->group(function () {
    Route::get("/", function (Request $request) {
        $st = new frendly_userController(Auth::user()->id);
        $r = $st->getFrendlyUser();
        return view("friends.index", ["friends" => $r]);
    })->name("friends.all")->middleware(["auth:web"]);

    Route::get("verify", function (Request $request) {
        $st = new frendly_userController(Auth::user()->id);
        $r = $st->get_friens_online();
        return view("friends.verify", ["friends" => $r]);
    })->name("friends.verify")->middleware(["auth:web"]);

    Route::get("/add", function (Request $request) {
        $request->merge([
            "vid_friend" => $request->vid_friend,
            "name_friend" => $request->name_friend
        ]);
        $validator = Validator::make($request->all(), [
            'vid_friend' => 'required|numeric',
            'name_friend' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->route("auth.register")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $st = new frendly_userController(Auth::user()->id, $request->vid_friend, $request->name_friend);
        $st->addFrendlyUser();
        return to_route("friends.all");
    })->name("friends.add")->middleware(["auth:web"]);

    Route::get("add-form", function (Request $request) {
        return view("friends.add");
    })->name("friends.add")->middleware(["auth:web"]);

    Route::post("add-form", function (Request $request) {
        $request->merge([
            "vid_friend" => $request->vid_friend,
            "name_friend" => $request->name_friend ?? null
        ]);
        $validator = Validator::make($request->all(), [
            'vid_friend' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->route("auth.register")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $st = new frendly_userController(Auth::user()->id, $request->vid_friend, $request->name_friend);
        $st->addFrendlyUser();
        return to_route("friends.all")->with("success", "Amis ajouté dans la liste");
    })->name("friends.add.post")->middleware(["auth:web"]);

    Route::get("add-friend", function (Request $request) {
        $request->merge([
            "host" => $request->host,
            "vid_friend" => $request->vid_friend,
            "name_friend" => $request->name_friend ?? "No infomation"
        ]);
        $validator = Validator::make($request->all(), [
            'vid_friend' => 'required|numeric',
        ]);
        $st = new frendly_userController(Auth::user()->id, $request->vid_friend, $request->name_friend);
        $st->addFrendlyUser();
        return redirect($request->host)->with("success", "Amis ajouté dans la liste");
    })->name("friends.add.oter.page.post")->middleware(["auth:web"]);

    Route::post("destroy/{id}", function (Request $request) {
        $request->merge([
            "id" => $request->id,
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route("auth.register")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $st = new frendly_userController(Auth::user()->id, $request->id);
        $remenber = $st->get_friends_via_id($request->id);
        $st->deleteFrendlyUser($request->id);
        return to_route("friends.all")->with("success", "le VID " . $remenber["vid_friend"] . " à été supprimé dans la liste !");
    })->name("friends.destroy")->middleware(["auth:web"]);

    Route::get("edit", function (Request $request) {
        $request->merge([
            "id" => $request->id
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->route("friends.add")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $st = new frendly_userController(Auth::user()->id);
        $r = $st->get_friends_via_id($request->id);
        return view("friends.edit", ["friends" => $r]);
    })->name("friends.edit")->middleware(["auth:web"]);

    Route::post("edit", function (Request $request) {
        $request->merge([
            "id" => $request->id,
            "vid_friend" => $request->vid_friend,
            "name_friend" => $request->name_friend
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'vid_friend' => 'required|numeric',
            'name_friend' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->route("friends.add")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $st = new frendly_userController(Auth::user()->id, $request->vid_friend, $request->name_friend);
        $st->updateFrendlyUser($request->id);
        return to_route("friends.all")->with("success", "Vous avez mofifier le VID " . $request->vid_friend . " avec l'information suivante " . $request->name_friend . "");
    })->name("friends.edit.post")->middleware(["auth:web"]);
})->middleware(["auth:web"]);

Route::get("vid/{vid}", function (Request $request) {
    $request->merge([
        "vid" => $request->vid
    ]);
    $validator = Validator::make($request->all(), [
        'vid' => 'required|numeric',
    ]);
    if ($validator->fails()) {
        return redirect()->route("auth.register")
            ->withErrors("Erreur for authentification is not valid")
            ->withInput();
    }
    $online = new myOnlineServeurController($request->vid);
    $online = $online->getVerrifOnlineServeur();
    return $online;
})->name("vid");


Route::get("online", function (Request $request) {
    $online = new myOnlineServeurController(auth::user()->vid);
    $online = $online->getVerrifOnlineServeur();
    return $online;
})->name("online")->middleware(["auth:web"]);

Route::prefix("feedback")->group(function () {
    Route::get("/", function (Request $request) {
        return view("feedback.index");
    })->name("feedback.index");

    Route::post("create", function (Request $request) {
        $github = new GithubController();
        $github = $github->send_issue($request);
        $request->merge([
            "user_id" => Auth::user()->id,
            "body" => $request->body,
            "link" => $github,
            "label" => $request->label
        ]);
        $discord = new DiscordContoller();
        $discord->send_feedback($request);
        return to_route("feedback.index")->with("success", "Votre feedback à été envoyé !");
    })->name("feedback.post");
})->middleware(["auth:web"]);

Route::get("test", function (Request $request) {
    $online = new myOnlineServeurController("661650");
    $online = $online->getVerrifOnlineServeur();
    return $online;
})->name("test");

Route::get("test2", function (Request $request) {
    $authivao = new AuthIVAOController();
    $oo = $authivao->sso($request);
    return $oo;
})->name("test2");

Route::get("test3", function (Request $request) {
    $whazzup = new whazzupController();
    $get_all_atc = $whazzup->Get_Position();
    $get_all_atc = $get_all_atc->json();
    $get_atc_online = $whazzup->ckeck_online_atc("LFBO");
    //recherche de Array $get_all_atc corespndant a liste $get_atc_online
    $get_atc_onlined = [];
    foreach ($get_all_atc as $key => $value) {
        foreach ($get_atc_online as $key2 => $value2) {
            if ($value["callsign"] == $value2["callsign"]) {
                $get_atc_onlined[] = $value;
            }
        }
    }
    return $get_atc_onlined;
})->name("test3");
