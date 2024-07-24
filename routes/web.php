<?php

use App\Mail\MailTest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Sleep;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\airac_info;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Monolog\Formatter\JsonFormatter;
use Illuminate\Support\Facades\Crypt;
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
use App\Http\Controllers\CarteSIAController;
use App\Http\Controllers\changelogController;
use App\Http\Controllers\EventIvaoController;
use App\Http\Controllers\PilotIvaoController;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Controllers\chartIvaoFRcontroller;
use App\Http\Controllers\frendly_userController;
use App\Http\Controllers\MailRegisterController;
use App\Http\Controllers\my_fav_plateController;
use App\Http\Requests\registerValidationRequest;
use App\Http\Controllers\myOnlineServeurController;
use App\Http\Controllers\CreatAuhUniqueUsersController;
use Symfony\Component\HttpKernel\Controller\ErrorController;

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
    $event_world = new EventIvaoController();
    $event_world = $event_world->get_event_ivao_RFE_RFO();
    $event_fr = new EventIvaoController();
    $event_fr = $event_fr->get_event_ivao_FR();
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
        return response()->view('welcome', ["whazzup" => $whazzup, "online" => $online, "update" => $u, "event_worl" => $event_world, "event_fr" => $event_fr]);
    }
    if (env("maintenance_mode") == true) {
        return view('maintenance');
    }
    $online = null;
    return response()->view('welcome', ["whazzup" => $whazzup, "online" => $online, "update" => $u, "event_worl" => $event_world, "event_fr" => $event_fr]);
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
})->middleware(["auth:web"]);

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
    })->name("pirep.upload")->middleware(["auth:web"]);

    Route::get("/show/{id}", function (Request $request) {
        $request->merge([
            "id" => $request->id
        ]);
        if (!Auth::user()) {
            return redirect()->route("auth.login");
        } else {

            $pirep_ivao = new whazzupController();
            $pirep = new PirepController();
            $oo = $pirep_ivao->get_fp($request->id);
            return view("pirep.show", ["json" => $oo]);
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
})->middleware(["auth:web"]);

Route::get("atc/{icao}", function (Request $request) {
    $request->merge([
        "icao" => $request->icao
    ]);
    $request->validate([
        "icao" => "required|size:4"
    ]);
    $atconline = new eventController($request->icao);
    $atc = $atconline->get_arrival_departure();
    $metar = new metarController();
    $metar = $metar->metar($request->icao);
    $info_atc = null;
    return view("plateforme.atc", ["icao" => $request->icao, "atc" => $atc, "metar" => $metar, "info_atc" => $info_atc]);
    
    
})->name("atc");

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
            "vid" => $request->vid,
            "myvid" => $request->myvid
        ]);
        $authivao = new AuthIVAOController();
        $authivao->sso($request, "home");
        $whazzup = new whazzupController();
        $whazzup->post_friends($request->myvid, $request->vid);
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
        // dd($request);
        // $request->merge([
        //     "user_id" => Auth::user()->id ?? null,
        //     "body" => $request->body,
        //     "link" => $github,
        //     "label" => $request->label
        // ]); 
        $github = $github->send_issue($request);
        $discord = new DiscordContoller();
        $discord->send_feedback($request);
        return to_route("feedback.index")->with("success", "Votre feedback à été envoyé !");
    })->name("feedback.post");
})->middleware(["auth:web"]);


Route::prefix("devs")->group(function () {

    Route::get("/ils", function (Request $request) {
        $airac = new airac_info();
        $airport = "LFBL";
        $Rwy = "21";
        $airac2 = $airac->get_approach($airport);
        $airac1 = $airac->get_departure($airport);
        $ils = $airac->get_ils_information($airport, $Rwy);

        $airac3 = collect(["departure" => $airac1, "arrival" => $airac2, "ils" => $ils]);

        return $airac3;
    });

    Route::get("vac", function (Request $request) {
        return view("vac.index");
    })->name("vac.index");

    Route::get("vac/{icao}", function (Request $request) {
        $request->merge([
            "icao" => $request->icao
        ]);
        $icao = strtoupper($request->icao);
        $regex_for_icao = "/[A-Za-z]{2}(?:\d{4}|[A-Za-z]{2})/";
        if (!preg_match($regex_for_icao, $icao)) {
            return response()->json(["error" => "Icao not valid"], 400);
        }
        if (strlen($icao) == 4) {
            $chart_sia = new CarteSIAController();
            $chart = $chart_sia->chartVFR($icao);
            if ($chart == null) {
                return response()->json(["error" => "Icao not valid"], 400);
            }
            $req = http::get($chart);
            if ($req->status() != 200) {
                return response()->json(["error" => "Icao not valid2"], 400);
            }
            return response($req->body(), 200)->header('Content-Type', 'application/pdf');
        }
        $req = http::get("https://basulm.ffplum.fr/PDF/" . $icao . ".pdf");
        if ($req->status() != 200) {
            return response()->json(["error" => "Icao not valid2"], 400);
        }
        return response($req->body(), 200)->header('Content-Type', 'application/pdf');
        //return $airac;
    })->name("vac.icao");

    Route::get("event", function (Request $request) {        
        $event_world = new EventIvaoController();
        $event_world = $event_world->get_event_ivao_RFE_RFO();
        return $event_world;
        
    })->name("event.index");

    Route::get("crypt", function (Request $request) {
        $encrypted = Crypt::encryptString('la vie de devs est cool.');
        $decrypted = Crypt::decryptString($encrypted);
        dd($encrypted, $decrypted);
    })->name("crypto");
    

    route::get("test/{id}", function (Request $request) {
        $request->merge([
            "id" => $request->id
        ]);
        $whazzup = new whazzupController();
        $whazzup = $whazzup->position_search($request->id);
        return $whazzup;

    })->name("test");
});

