<?php

namespace App\Http\Controllers;

use App\Models\loggin;
use App\Models\ApiUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\logginController;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

class ApiGestionController extends Controller
{
    
    public function Create_client_id(){
        $sclient_id = Str::random(15);
        return $sclient_id;
    }

    public function Create_client_secret(){
        $token = Str::random(26);
        return $token;
    }

    public function lvl_users(){
        $lvl = auth()->user()->role;
        if($lvl == 1 || $lvl == 2 || $lvl == 3){
            $lvl_users = "1";
        }
        if($lvl == 4 || $lvl == 5 || $lvl == 6){
            $lvl_users = "2";
        }
        if($lvl == 7 || $lvl == 8){
            $lvl_users = "3";
        }
        if($lvl == 9 || $lvl == 10){
            $lvl_users = "4";
        }
        return $lvl_users;
    }

    public function return_information(){
        $information = array(
            "client_id" => $this->Create_client_id(),
            "token" => $this->Create_client_secret(),
            "role" => $this->lvl_users(),
        );
        return $information;
    }

    public function check_Informations($id){
        
        $check = ApiUsers::where('users_id', $id)->first();
        if($check != null){
            return $check;
        }else{
            $information = array(
                "role" => 0,
            );
            return $information;
        }
    }

    public function creat_keys_api(){
        $information = $this->return_information();
        $api = new ApiUsers();
        $api->client_id = $information['client_id'];
        $api->token = $information['token'];
        $api->users_id = auth()->user()->id;
        $api->email = auth()->user()->email;
        $api->role = $information['role'];
        $api->save();
        return $information;
    }

    public function get_keys_api(){
        $information = $this->check_Informations(auth()->user()->id);
        if($information['visible'] == true){
           
        }
        return $information;
    }

    public function delete_keys_api(){
        $api = ApiUsers::where('users_id', auth()->user()->id)->first();
        $api->delete();
            
    }

    Public function update_visible(){
        $api = ApiUsers::where('users_id', auth()->user()->id)->first();
        $api->visible = false;
        $api->save();
    }

    public function verifyToken(Request $request){
        $api = ApiUsers::where('token', $request->bearerToken())->first();
        if($api != null){
            $loogin = new logginController();
            $loogin->infoLog("Connexion API", $api->users_id, $request->ip(), $api->users_id);
            return true;
        }else{
            return false;
        }
    }
}
