<?php

use App\Models\whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logginController;
use symfony\component\httpfoundation\cookie;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
use App\Http\Controllers\frendly_userController;
use App\Http\Controllers\whazzupController;
use App\Models\ApiUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie as FacadesCookie;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/test', function (Request $request) {
    Http::withToken("Bearer " . $request->bearerToken());
    $value = $request->header('Client-Id');

    if (FacadesCookie::get("Auth") == true) {
        return [
            "auth" => "Hello",
            "value" => $value,
            "cookie" => FacadesCookie::get('Client-Id'),
            "bearer" => FacadesCookie::get('Bearer'),
            "auth" => FacadesCookie::get('Auth')
        ];
    }

    if ($request->bearerToken() == "123456789") {
        /** Cookie create session  */
        $response_Set_Cookie = cookie::create('Client-Id', $value, 0, null, null, false, false);
        $response_Set_Cookie_Bearer = cookie::create('Bearer', $request->bearerToken(), 0, null, null, false, false);
        $response_Set_Cookie_Auth = cookie::create('Auth', true, 0, null, null, false, false);
        $response = response('Hello World')->withCookie($response_Set_Cookie)->withCookie($response_Set_Cookie_Bearer)->withCookie($response_Set_Cookie_Auth);
        return $response;
    }
});



Route::get("/friends", function (Request $request) {
    $verifyToken = new ApiGestionController();
    $verifyToken = $verifyToken->verifyToken($request);
    if ($verifyToken) {
        Http::withToken("Bearer " . $request->bearerToken());
        if ($request->bearerToken() == $verifyToken) {
            $whazzup = new whazzupController();
            $whazzup = $whazzup->getwhazzup();
            $user_id = ApiUsers::where("token", $request->bearerToken())->first();
            $friends = new frendly_userController($user_id->users_id);
            $friends = $friends->count_verification_API();
            return $friends;
        } 
    } else {
        return [
            "auth" => false,
            "message" => "You are not authorized to access this page",
            "error" => "401 Unauthorized",
            "status" => "401",
            "Token" => "Bearer is not valid"
        ];
    }
});


Route::get("/whazzup", function(Request $request){
    $whazzup = new whazzupController();
    $whazzup = $whazzup->getwhazzup();
});