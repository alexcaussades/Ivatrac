<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtcController extends Controller
{
    function new(){
        
        return response()->json([
            "status" => "success"
        ]);
    }

    function add(Request $request){
        $vid = $request->input("vid");

        return response()->json([
            "status" => "success",
            "vid" => $vid
        ]);
    }
}
