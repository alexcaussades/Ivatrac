<?php

use App\Http\Controllers\api\usersApiController as ApiUsersApiController;
use App\Http\Controllers\usersApiController;
use App\Models\whitelist;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\whitelistResource;
use App\Http\Controllers\whitelistController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get("/", function () {
    return [
        "Route" => [
            "GET" => [
                "/whitelist",
                "/whitelist/{id}",
                "/whitelist/name/{name}"
            ],
            "POST" => [
                "/whitelist"
            ],
            "PUT" => [
                "/whitelist/{id}"
            ],
            "DELETE" => [
                "/whitelist/{id}"
            ]
        ],
        "whitelist" => [
            "id" => "int",
            "name_rp" => "string",
            "name" => "string",
            "steam" => "string",
            "discord" => "string",
            "reason" => "string",
            "date" => "string",
            "status" => "string",
            "created_at" => "string",
            "updated_at" => "string"
        ],
        "user" => [
            "id" => "int",
            "name" => "string",
            "email" => "string",
            "email_verified_at" => "string",
            "created_at" => "string",
            "updated_at" => "string"
        ],
        "auth" => [
            "login" => [
                "email" => "string",
                "password" => "string"
            ],
            "register" => [
                "name" => "string",
                "email" => "string",
                "password" => "string",
                "password_confirmation" => "string"
            ]
        ]
    ];
});

Route::get("/whitelist", function () {
    return whitelistResource::collection(whitelist::all());
});

Route::get("/whitelist/{id}", function (Request $request) {
    return new whitelistResource(whitelist::find($request->id));
});

Route::get("/whitelist/name/{name}", function (Request $request) {
    /** search name and jocker */
    $name = $request->name;
    return whitelistResource::collection(whitelist::where('name_rp', 'LIKE', '%' . $name . '%')->get());
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/whitelist", [whitelistController::class, 'store']);
Route::put("/whitelist/{id}", [whitelistController::class, 'update']);
Route::delete("/whitelist/{id}", [whitelistController::class, 'destroy']);
