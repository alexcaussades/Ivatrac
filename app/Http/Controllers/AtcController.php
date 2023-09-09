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
        $whazzup = new whazzupController();
        $p = $whazzup->get_rwy($icao);
        return $p;

    }

}
