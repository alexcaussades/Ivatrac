<?php

namespace App\Http\Controllers;


use App\Models\pirep;
use Illuminate\Support\Facades\Auth;

class PirepController extends Controller
{
    

    Public function fpl_ivao($request){
        
        $pirep = $request->file("fpl");
        $pirepon = $pirep->store("pirep", "public");
        $explose = file_get_contents(storage_path("app/public/" . $pirepon));
        $explose = explode("\n", $explose);
        /** suprimer des carateres */
        $sup = ["\r", "\n"];
        $explose = str_replace($sup, "", $explose);
        $explose2 = str_replace("=", ":", $explose);
        $pirep = [];
        
        foreach ($explose2 as $key => $value) {

            for ($i = 0; $i < count($explose2); $i++) {
                $explose2[$key] = explode(":", $value);
                $explose2[$key][1] = $explose2[$key][1] ?? Null;
                $pirep[$explose2[$key][0]] = $explose2[$key][1];
            }
        }
        json_encode($pirep);
        return $pirep;
    }

    Public function store_fpl($value){
        //TODO: Resolve auth user  
        $value = $this->fpl_ivao($value);
        $users_id = Auth::user()->id;
        $value2 = [$value];
        $pirep = new pirep();
        $pirep->users_id = $users_id;
        $pirep->departure = $value["DEPICAO"];
        $pirep->arrival = $value["DESTICAO"];
        $pirep->aircraft = $value["ACTYPE"];
        $pirep->fpl = json_encode($value2);
        $pirep->save();

    }

    Public function show_fpl(){
        $pirep = pirep::all();
        return $pirep;
    }

    public function show_fpl_id($id){
        $pirep = pirep::find($id);
        return $pirep;
    }
}
