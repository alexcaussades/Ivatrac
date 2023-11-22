<?php



use App\Mail\MailTest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Sleep;
use Illuminate\Support\Carbon;
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
use App\Http\Controllers\eventController;
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
use App\Http\Controllers\AuthIVAOController;
use App\Http\Controllers\changelogController;
use App\Http\Controllers\PilotIvaoController;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Controllers\chartIvaoFRcontroller;
use App\Http\Controllers\frendly_userController;
use App\Http\Controllers\MailRegisterController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\myOnlineServeurController;
use App\Http\Controllers\CreatAuhUniqueUsersController;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use App\Http\Controllers\my_fav_plateController;

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

if (env("maintenance_mode") == true) {
    Route::get('/', function () {
        return view('maintenance');
    });
    route::get('/{any}', function () {
        return view('maintenance');
    })->where('any', '.*');
}

Route::get('/', function (Request $request) {
    /** creation d'un cookie sur laravel */
    $whazzup = new whazzupController();
    $whazzup = $whazzup->connexion();
    $w = new changelogController();
    $u = $w->info_update();
    if (Session::get("ivao_tokens") != null) {
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('UTC'));
        $date = $date->format('Y-m-d H:i:s');
        if (Session::get("ivao_tokens")["expires_in"] < $date) {
            Session::forget("ivao_tokens");
            return to_route("ivao.connect");
        }
        $whaz = new whazzupController();
        $online = $whaz->online_me();
        $users_me = $whaz->user_me();
        //dd($online->json(), $users_me);
        $online = json_decode($online, true);
        return response()->view('welcome', ["whazzup" => $whazzup, "online" => $online, "update" => $u ]);
    }
    if (env("maintenance_mode") == true) {
        return view('maintenance');
    }
    $online = null;
    return response()->view('welcome', ["whazzup" => $whazzup, "online" => $online, "update" => $u]);
})->where('client', '[0-9]+')->name("home");

Route::get('/logout', function (Request $request) {
    return to_route("auth.logout");
})->name("logout");

Route::get('/changelog', function (Request $request) {
    $change = new changelogController();
    $json = $change->localadress();
    return view('changelog', ['data' => $json]);
})->name("changelog");

Route::get("callback", function (Request $request) {
    $request->merge([
        "code" => $request->code
    ]);
    $validator = Validator::make($request->all(), [
        'code' => 'required|string',
    ]);
    $authivao = new AuthIVAOController();
    $oo = $authivao->sso($request, "home");
    return $oo;
})->name("callback");

Route::prefix("auth/")->group(function () {

    Route::get("login", function () {
        if (Auth::user() != null) {
            return redirect()->route("serveur");
        }
        return redirect()->route("ivao.connect");
    })->name("auth.login");

    Route::post("login", [\App\Http\Controllers\usersController::class, "autentification"]);

    Route::get('verif-email/{token}', function (Request $request, usersController $usersController) {
        $usersController->verif_email($request);
        return redirect()->route("auth.login");
    })->name('auth.verif-email');

    Route::get("logout", function (usersController $usersController, Request $request) {
        $usersController->logout($request);
        return redirect()->route("home");
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
    })->name("serveur.api")->middleware(["auth:admin"]);

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
        
        $regex_for_vid = "/^[0-9]{1,6}$/";
        if (preg_match($regex_for_vid, $request->icao)) {
            return redirect()->route("vid", ["vid" => $request->icao]);
        }

        $request->validate([
            "icao" => "required|size:4"
        ]);
        $icao = strtoupper($request->icao);
        $metarController = new metarController();
        $wazzup = new whazzupController();
        $metar = $metarController->metar($icao);
        $bookings = $wazzup->get_bookings_for_event($icao);
        $taf = $metarController->taf($icao);
        $ATC = $wazzup->ckeck_online_atc($icao);
        $pilot = $wazzup->get_traffics_count($icao);

        if ($metar == NULL || $taf == NULL || $ATC == NULL || $pilot == NULL) {
            return view("vid", ["icao" => $icao]);
        }

        return view("metar.icao", ["metar" => $metar, "taf" => $taf, "atc" => $ATC, "pilot" => $pilot, "bookings" => $bookings, "icao" => $icao]);
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

    Route::get("/bookings", function (Request $request) {
        $authivao = new AuthIVAOController();
        $authivao->sso($request, "ivao.bookings");
        $whazzup = new whazzupController();
        $bookings = $whazzup->Bookings();
        $date = date("d/m/Y");
        return view("ivao.bookings", ["bookings" => $bookings, "date" => $date]);
    })->name("ivao.bookings")->middleware(["auth:web"]);

    Route::get("connect", function (Request $request) {
        $authivao = new AuthIVAOController();
        $oo = $authivao->sso($request);
        return $oo;
    })->name("ivao.connect");
});

Route::prefix("fpl")->group(function () {
    Route::get("/", function (Request $request) {
        $whazzup = new whazzupController();
        $pirep = $whazzup->get_fp_me();
        $pirep = $pirep["items"];
        return view("pirep.index", ["pireps" => $pirep]);
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
            $whazzup = new whazzupController();
            $json = $whazzup->get_fp_me();
            dd($json);
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
        if (empty(Session::get("ivao_tokens"))) {
            return redirect()->route("ivao.connect");
        }
        $authivao = new AuthIVAOController();
        $authivao->sso($request, "friends.verify");
        $whazzup = new whazzupController();
        $friends = $whazzup->get_friends_online();
        return view("friends.verify", ["friends" => $friends]);
    })->name("friends.verify")->middleware(["auth:web"]);


    Route::get("post-webeye", function (Request $request) {
        $request->merge([
            "vid" => $request->vid
        ]);
        $authivao = new AuthIVAOController();
        $authivao->sso($request, "home");
        $whazzup = new whazzupController();
        $whazzup->post_friends($request->vid);
        return to_route("friends.all")->with("success", "Amis ajouté dans la liste");
    })->name("friends.add.post.webeye")->middleware(["auth:web"]);

    Route::get("add-form", function (Request $request) {
        return view("friends.add");
    })->name("friends.add")->middleware(["auth:web"]);

    Route::get("destroy", function (Request $request) {
        $request->merge([
            "id" => $request->vid,
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->route("auth.register")
                ->withErrors("Erreur for authentification is not valid")
                ->withInput();
        }
        $whazzup = new whazzupController();
        $whazzup->delete_friends($request->id);
        return to_route("friends.all")->with("success", "Friends remove in your list");
    })->name("friends.destroy")->middleware(["auth:web"]);
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

Route::prefix("event")->group(function () {
    Route::get("/ximea", function (Request $request) {
        $airport = "LFMT";
        $wazzup = new whazzupController();
        $bookings = $wazzup->get_bookings_for_event($airport);
        $event = new eventController($airport);
        $metars = new metarController();
        $metar = $metars->metar($airport);
        $taf = $metars->taf($airport);
        $r = $event->get_general();
        return view("event.ximea.index", ["r" => $r, "bookings" => $bookings, "metar" => $metar["metar"], "taf" => $taf["taf"]]);
    })->name("event.ximea");

    Route::get("/{id}", function (Request $request) {
        $whazzup = new whazzupController();
        $event = $whazzup->get_event_id($request->id);
        return view("event.show", ["event" => $event]);
    })->name("event.show");
});

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
            "user_id" => Auth::user()->id ?? null,
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
    $w = new whazzupController();
    $u = $w->get_rwys("LFBO");
    $g = json_decode($u, true);
    $unit_pi = "ft";
    $unit_km = "km";
    $rwy = [];
    for ($i = 0; $i < count($g); $i++) {
        $rwy[$i]["runway"] = $g[$i]["runway"];
        $rwy[$i]["length_pi"] = $g[$i]["length"];
        $rwy[$i]["length_KM"] = $g[$i]["length"] / 3281;
        $rwy[$i]["length_KM"] = round($rwy[$i]["length_KM"], 2);
    }
    $data = [
        "rwy" => $rwy,
        "unit_pi" => $unit_pi,
        "unit_km" => $unit_km
    ];
    $data = json_encode($data);
    return $data;
});


Route::get("test2", function (Request $request) {
    $w = new whazzupController();
    $u = $w->event_ivao();
    $g = json_decode($u);
    return $g;
});

Route::get("test3", function (Request $request) {
    $fav = new my_fav_plateController();
    $fav = $fav->get();
    return $fav;
});