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
        return $aircrafts["wakeTurbulence"];
    }

    public function aircrafts_model($icao_code){
        $whazzup = new whazzupController();
        $aircrafts = $whazzup->get_aircrafts($icao_code);
        return $aircrafts["model"];
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
            $sr[$i]["star"] = $this->Star($r["route"]);
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

    public function Star($route){
        $star_search = $this->get_last_arrival($route);

        switch ($star_search) {
            case 'MEN':
                $star_search = "MEN 6T";
                return $star_search;
                break;
            
            case 'BRUSC':
                $star_search = "BRUSC 6T";
                return $star_search;
                break;
            
            case 'KELAM':
                $star_search = "KELAM 6T";
                return $star_search;
                break;

            case 'PPG':
                $star_search = "PPG 6T";
                return $star_search;
                break;

            case 'MARRI':
                $star_search = "MARRI 6T";
                return $star_search;
                break;
            
            case 'NG':
                $star_search = "NG 6T";
                return $star_search;
                break;

            default:
                return $star_search;
                break;
        }
    }

    public function Departure(){
        $q = $this->get_arrival();
        $r = $q["outbound"];
        $sr = [];
       // filter la liste avec les parametres dans la liste [lastTrack][altitude] avec [lastTrack][onGround]
        for ($i = 0; $i < count($r); $i++) {
            if ($r[$i]["lastTrack"]["onGround"] == true) {
                $sr[$i]["callsign"] = $r[$i]["callsign"];
                $sr[$i]["model"] = $this->aircrafts_model($r[$i]["flightPlan"]["aircraftId"]);
                $sr[$i]["wakeTurbulence"] = $this->aircrafts($r[$i]["flightPlan"]["aircraftId"]);
                $sr[$i]["arrival"] = $r[$i]["flightPlan"]["arrivalId"];
                $sr[$i]["fp"] = $this->get_fp($r[$i]["id"]);
                $sr[$i]["departureTime"] = $this->get_fp($r[$i]["id"])[0]["departureTime"];
                $sr[$i]["departureTime"] = Carbon::parse($sr[$i]["departureTime"])->format('H:i');
                $sr[$i]["peopleOnBoard"] = $this->get_fp($r[$i]["id"])[0]["peopleOnBoard"];
                $sr[$i]["rule"] = $this->get_fp($r[$i]["id"])[0]["flightRules"];
                $sr[$i]["fp"] = null;
            }
        }
        $sr = array_values($sr);
        $query = [
            "count" => count($sr),
            "data" => $sr
        ];
        return $query;
    }

    public function get_atc_online(){
        $whazzup = new whazzupController();
        $atc = $whazzup->position_search($this->icao);
        $sy = [];
        for ($i = 0; $i < count($atc); $i++) {
            $sy[$i] = $atc[$i];
        }
        $sy = collect($sy)->toArray();
        $r = new whazzupController();
        $r = $r->atc_tracking();
        $sr = [];
        for ($i = 0; $i < count($r); $i++) {
            if ($r[$i]["callsign"]) {
                $sr[$i]["callsign"] = $r[$i]["callsign"];
                $sr[$i]["time"] = Carbon::parse($r[$i]["time"])->format('H:i');
                $sr[$i]["frequency"] = $r[$i]["atcSession"]["frequency"];
                $sr[$i]["id"] = $r[$i]["id"];
            }
        }
        $sr = collect($sr)->toArray();
        // rechercher dans la liste sr les callsigns qui sont dans la liste sy et les mettre dans une liste
        $online = [];
        foreach ($sr as $key => $value) {
            if (in_array($value["callsign"], $sy)) {
                array_push($online, $value);
            }
        }
         return $online;
    }

    public function get_arrival_departure(){

        $arrival = $this->get_general();
        $departure = $this->Departure();
        $atc = $this->get_atc_online();
        $query = [
            "arrival" => $arrival,
            "departure" => $departure,
            "atc" => $atc
        ];
        return $query;
    }
}
