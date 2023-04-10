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
});

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

Route::get('/atc', function (AtcController $atcController) {
    return $atcController->new();
});

Route::get('/atc/add/{vid}', function (AtcController $atcController, Request $request) {
    
    $request->merge([
        "vid" => $request->vid
    ]);
    
    $essais_bdd = new \App\Models\Essais();
    $essais_bdd->name = $request->vid;
    $essais_bdd->save();

    return redirect("atc/view");
});

Route::get('discord', function (DiscordNotfyController $discordNotfyController) {
    return $discordNotfyController->PostNotify2();
});
    

Route::get('/atc/view', function (AtcController $atcController) {
    $essais_bdd = \App\Models\Essais::all(["id", "name"]);
    return $essais_bdd;
})->name("atc.view");

Route::get('/atc/view/{id}', function (AtcController $atcController, Request $request) {
    $essais_bdd = \App\Models\Essais::find($request->id);
    return $essais_bdd;
});

Route::get('/atc/delect/{id}', function(AtcController $atcController, Request $request) {
    $essais_bdd = \App\Models\Essais::find($request->id);
    $essais_bdd->delete();
    return redirect("atc/view");
});

Route::prefix("auth/")->group(function () {
    Route::get("add", [CreatAuhUniqueUsersController::class, "creatAuthUniqueUses"]);
    Route::get("verif/{id}", function (Request $request, CreatAuhUniqueUsersController $creatAuhUniqueUsersController) {
        return $creatAuhUniqueUsersController->verifid($request);
    });
    Route::get("delete", [CreatAuhUniqueUsersController::class, "deleteUID"]);
});
