<?php

namespace App\Http\Controllers;

use App\Models\metar_favModel;
use Illuminate\Http\Request;

class my_fav_plateController extends Controller
{
    public function save(Request $request)
    {
        $icao = $request->icao;
        $vid = $request->vid;
        $id_user = auth()->user()->id;
        $fav = metar_favModel::where("vid", $vid);
        if ($fav == null) {
            $fav = new \App\Models\metar_favModel();
            $fav->icao = $icao;
            $fav->vid = $vid;
            $fav->id_user = $id_user;
            $fav->save();
            return response()->json(["status" => "success", "message" => "Saved"]);
        } else {
            $fav->delete();
            return response()->json(["status" => "success", "message" => "Deleted"]);
        }        
    }

    public function get()
    {
        $id_user = auth()->user()->id;
        $fav = metar_favModel::where("id_user", $id_user)->get();
        $plateforme = [];
        foreach ($fav as $key => $value) {
            $plateforme[] = $value->icao;
        }
        return $plateforme;
    }
    
    public function update(Request $request)
    {
        $icao = $request->icao;
        $vid = $request->vid;
        $id_user = auth()->user()->id;
        $fav = metar_favModel::where("vid", $vid)->where("id_user", $id_user)->first();
        $fav->icao = $icao;
        $fav->save();
        return response()->json(["status" => "success", "message" => "Updated"]);
    }
}
