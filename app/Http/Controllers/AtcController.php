<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    
}
