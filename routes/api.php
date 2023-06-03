<?php

use App\Models\whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logginController;
use symfony\component\httpfoundation\cookie;
use App\Http\Controllers\whitelistController;
use App\Http\Controllers\ApiGestionController;
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

Route::get('/whitelist/{id}', function (Request $request) {
    $request->merge([
        'id' => $request->id,
        'value' => $request->header('Client-Id')
    ]);
    $loggin = new logginController();
    $verifyToken = new ApiGestionController();
    $verifyToken = $verifyToken->verifyToken($request);
    if ($verifyToken) {
        Http::withToken("Bearer " . $request->bearerToken());
        if ($request->bearerToken() == $verifyToken) {
            $whitelist = whitelist::where('id', $request->id)->get();
            $users = new ApiGestionController();
            $users = $users->ckeck_users($request);
            if ($whitelist == !null) {
                $loggin->req_api("Get whitelist with id: " . $request->id, $users->users_id, $request->ip(), 0);
                return whitelist::where('id', $request->id)->get();
            } else {
                $loggin->warningLog("No id found with this whitelist: " . $request->id, $users->users_id, $request->ip(), 0);
                return [
                    "message" => "You are not authorized to access this page",
                    "status" => "404",
                    "error" => " id not found",
                ];
            }
        }
    } else {
        $loggin->warningLog("Bearer is not valid for the request on id: " . $request->id, 0, $request->ip(), 0);
        return [
            "auth" => false,
            "message" => "You are not authorized to access this page",
            "error" => "401 Unauthorized",
            "status" => "401",
            "Token" => "Bearer is not valid"
        ];
    }
});
