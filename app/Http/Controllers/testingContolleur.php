<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Sleep;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class testingContolleur extends Controller
{
    public function create_pass($value)
    {
        $test = $value;
        $name = Str::random(10);
        $p = [
            "id" => $test,
            "ip" => request()->ip(),
            "user-agent" => request()->header('User-Agent'),
            "time" => date("Y-m-d H:i:s"),
            "token" => Str::random(60),
            "pass" => Str::password(10),
            "account" => [
                "name" => $name,
                "email" => $name . "@gmail.com",
            ]
        ];
        $p = json_encode($p);
        Storage::disk('local')->put("password/" . $test . ".json", $p);
        Sleep::for(1)->second();
        return to_route('welcome');
    }

    /** 
     * Donwload file the login and redirect to page for welcome
     * @param string $value
     * @return \Illuminate\Http\Response
     */

    public function download($value){
        //$file = Storage::download("password/" . $value . ".json");
        //return $file;
        return Storage::download("password/" . $value . ".json");
        

    }
}
