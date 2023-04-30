<?php

use App\Http\Controllers\whitelistController;
use App\Models\whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\whitelistResource;

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


Route::get("/", function(){
    return whitelistResource::collection(whitelist::all());
   
});

Route::get("/whitelist/{id}", function(Request $request){
    return new whitelistResource(whitelist::find($request->id));   
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
