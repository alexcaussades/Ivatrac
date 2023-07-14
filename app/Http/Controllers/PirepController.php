<?php

namespace App\Http\Controllers;


use App\Models\pirep;
use Illuminate\Support\Facades\Auth;

class PirepController extends Controller
{


    public function fpl_ivao($request)
    {

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

    public function StoreChangeArray($value)
    {
        $newStrore = [
            "id" => $value["ID"],
            "identification" => $value["DEPICAO"]."-".$value["DESTICAO"],
            "departureAerodrome" => $value["DEPICAO"],
            "destinationAerodrome" => $value["DESTICAO"],
            "aircraftType" => $value["ACTYPE"],
            "upload" => $value["upload"] ?? Null,
            "route" => $value["ROUTE"] ?? Null,
            "flightRules" => $value["RULES"],
            "typeOfFlight" => $value["FLIGHTTYPE"],
            "pob" => $value["POB"],
            "eet" => $value["EET"],
            "Alternate" => $value["ALTICAO"],
            "Alternate2" => $value["ALTICAO2"],
            "equipment" => $value["EQUIPMENT"],
            "level" => $value["LEVEL"],
            "LevelFL" => $value["LEVELTYPE"],
            "speednumber" => $value["SPEED"],
            "speed" => $value["SPEEDTYPE"],
            "departureTime" => $value["DEPTIME"],
            "wakeTurbulence" => $value["WAKECAT"],
            "endurance" => $value["ENDURANCE"],
            "Other" => $value["OTHER"],
        ];
        $create = json_encode($newStrore);
        return $create;
    }

    public function store_fpl($value)
    {
        $value = $this->fpl_ivao($value);
        $value2 = $this->StoreChangeArray($value);
        $users_id = Auth::user()->id;
        $pirep = new pirep();
        $pirep->users_id = $users_id;
        $pirep->departure = $value["DEPICAO"];
        $pirep->arrival = $value["DESTICAO"];
        $pirep->aircraft = $value["ACTYPE"];
        $pirep->upload = 1;
        $pirep->fpl = $value2;
        $pirep->save();
    }

    public function show_fpl()
    {
        $pirep = pirep::all();
        return $pirep;
    }

    public function show_fpl_id($id)
    {
        $pirep = pirep::find($id);
        return $pirep;
    }

    public function create_for_website($value)
    {
        $pirep = new pirep();
        $pirep->users_id = $value["users_id"];
        $pirep->departure = $value["departureAerodrome"];
        $pirep->arrival = $value["destinationAerodrome"];
        $pirep->aircraft = $value["aircraftType"];
        $pirep->fpl = json_encode($value);
        $pirep->save();
    }

    public function show_fpl_user($id)
    {
        $pirep = pirep::where("users_id", $id)->get();
        return $pirep;
    }

    public function find_route($id)
    {
        $pirep = $this->show_fpl_id($id);
        $pirep = json_decode($pirep->fpl, true);
        if ($pirep->route == null) {
            $pirep->route = $pirep[0]->ROUTE;
        }
        return $pirep;
    }
}
