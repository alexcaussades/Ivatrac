<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AtcController extends Controller
{
    function new()
    {

        return response()->json([
            "status" => "success"
        ]);
    }

    function add(Request $request)
    {
        $vid = $request->input("vid");
        $validateur = Validator::make($request->all(), [
            'vid' => 'required|min:6|integer'
        ]);
        //dd($validateur->fails());
        if ($validateur->fails()) {
            return response()->json([
                "status" => "error",
                "message" => "vid invalide"
            ]);
        } else {
            return response()->json([
                "status" => "success",
                "vid" => $vid
            ]);
        }
    }

    public function datediff($dateIVAO)
    {
        $date = date($dateIVAO);
        $local = date(now());
        $datediff = strtotime($local) - strtotime($date);
        $datediff = date("H:i:s", $datediff);
        return $datediff;
    }

    public function getRwy($icao)
    {
        $r = $icao['atis']['lines'];
        
        $r = explode(",", $r);
        $r = array_values($r); 
        $r = str_replace('"', " ", $r);
        $r = str_replace('[', " ", $r);
        $r = str_replace(']', " ", $r);
        return $r[4];
    }

    public function getRwyAPP($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao)) {
            return null;
        } else {
            $APP = $this->getRwy($icao);
            return $APP;
        }
    }

    public function getRwyTWR($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao)) {
            return null;
        } else {
            $TWR = $this->getRwy($icao);
            return $TWR;
            
        }
    }

    public function getRwyGND($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao)) {
            return null;
        } else {
            $GND = $this->getRwy($icao);
            return $GND;
        }
    }

    public function getRwyFSS($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao)) {
            return null;
        } else {
            $FSS = $this->getRwy($icao);
            return $FSS;
        }
    }

    public function resolve($icao)
    {
        $APP = $this->getRwyAPP($icao["APP"]);
        $TWR = $this->getRwyTWR($icao["TWR"]);
        $GND = $this->getRwyGND($icao["GND"]);
        $FSS = $this->getRwyFSS($icao["FSS"]);

        $r = array(
            "APP" => $APP,
            "TWR" => $TWR,
            "GND" => $GND,
            "FSS" => $FSS
        );
        return $r;
    }
}
