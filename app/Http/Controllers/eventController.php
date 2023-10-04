<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class eventController extends Controller
{
    public $icao;

    public function  __construct($icao = "LFBL")
    {
        $this->icao = $icao;
    }

    public function get_arrival()
    {
        $whazzup = new whazzupController();
        $trafic = $whazzup->get_traffics($this->icao);
        return $trafic;
    }

    public function get_fp($id_fp)
    {
        $whazzup = new whazzupController();
        $r = $whazzup->get_flightPlans($id_fp);
        return $r;
    }

    public function get_last_arrival($route)
    {
        $q = $route;
        $str = $q;
        $str = explode(" ", $str);
        $rr = [];
        for ($i = 0; $i < count($str); $i++) {
            if ($str[$i]) {
                array_push($rr, $str[$i]);
            }
        }
        $m = count($rr) - 1;
        return $rr[$m];
    }

    public function aircrafts($icao_code){
        $whazzup = new whazzupController();
        $aircrafts = $whazzup->get_aircrafts($icao_code);
        //dd($aircrafts);
        return $aircrafts["wakeTurbulence"];
    }

    public function ETA($distance_arrival, $speed)
    {
        $distance_arrival = explode(".", $distance_arrival);
        $speed = $speed / 60 ?? 1;
        if ($speed <= 0) {
            $speed = 1;
        }
        if ($distance_arrival <= 0) {
            $distance_arrival[0] = 1;
        }
        $arrival_time = $distance_arrival[0] / $speed;
        $arrival_time = Carbon::now()->addMinutes($arrival_time)->format('H:i');
        return $arrival_time;
    }

    public function get_general()
    {
        $q = $this->get_arrival();
        $q = $q["inbound"];
        //dd($q);
        $sr = [];
        for ($i = 0; $i < count($q); $i++) {
            //dd($q[$i]);
            $r = $this->get_fp($q[$i]["id"]);
            $r = $r[0];
            $sr[$i]["callsign"] = $q[$i]["callsign"];
            $sr[$i]["star"] = $this->get_last_arrival($r["route"]);
            $sr[$i]["eta"] = $this->ETA($q[$i]["lastTrack"]["arrivalDistance"], $q[$i]["lastTrack"]["groundSpeed"]);
            $sr[$i]["wakeTurbulence"] = $this->aircrafts($q[$i]["flightPlan"]["aircraftId"]);
        }
        $sr = array_values($sr);
        //tri par ordre eta croissant
        $sr = collect($sr)->sortBy('eta')->toArray();
        $query = [
            "count" => count($sr),
            "data" => $sr
        ];
        return $query;
        
    }

}
