<?php

use App\Http\Controllers\AtcController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreatAuhUniqueUsersController;
use App\Http\Controllers\DiscordNotfyController;
use Illuminate\Support\Facades\Validator;

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

Route::get('/welcome', function () {
    return view('welcome');
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
    Route::get("login", function(){
        return view("auth.login");
    })->name("auth.login");
    
    Route::post("login", function(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return redirect()->route("auth.login")
                        ->withErrors($validator)
                        ->withInput();
        }
        

        return redirect()->route("auth.login");
    });

    Route::get("register", function(){
        return view("auth.register");
    })->name("auth.register");
});
