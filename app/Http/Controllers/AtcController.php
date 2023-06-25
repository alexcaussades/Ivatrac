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
        //retouver le mot ARR dans le string
        $count = count($icao);
        for ($i = 0; $i < $count; $i++) {
            if (strpos($icao[$i], 'ARR') !== false) {
                $rwy = $icao[$i];
                $rwy = explode("/", $rwy);
            }
        }
        return $rwy;
    }

    public function getRwyAPP($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao["APP"]["atis"]["lines"])) {
            return null;
        } else {
           $APP = $this->getRwy($icao["APP"]["atis"]["lines"]);
            return $APP;
        }
    }

    public function getRwyTWR($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao["TWR"]["atis"]["lines"])) {
            return null;
        } else {
            $TWR = $this->getRwy($icao["TWR"]["atis"]["lines"]);
            return $TWR;
        }
    }

    public function getRwyGND($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao["GND"]["atis"]["lines"])) {
            return null;
        } else {
            $GND = $this->getRwy($icao["GND"]["atis"]["lines"]);
            return $GND;
        }
    }

    public function getRwyFSS($icao)
    {
        //retouver le mot ARR dans le string
        if (empty($icao["FSS"]["atis"]["lines"])) {
            return null;
        } else {
            $FSS = $this->getRwy($icao["FSS"]["atis"]["lines"]);
            return $FSS;
        }
    }

    public function resolve($icao){
        $APP = $this->getRwyAPP($icao);
        $TWR = $this->getRwyTWR($icao);
        $GND = $this->getRwyGND($icao);
        $FSS = $this->getRwyFSS($icao);

        $r = array(
            "APP" => $APP,
            "TWR" => $TWR,
            "GND" => $GND,
            "FSS" => $FSS
        );
        return $r;
    }
}
