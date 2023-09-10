<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\metarController;

class myOnlineServeurController extends Controller
{
    public $vid;
    public $id;
    public $option;


    public function __construct($id, $vid = null)
    {
        $this->vid = $vid ?? null;
        $this->id = $id;
    }

    public function get_VID()
    {
        $myOnlineServeur = new usersController();
        $users = $myOnlineServeur->get_info_user($this->id);
        return $users->vid;
    }

    public function get_whazzup()
    {
        $whazzup = new whazzupController();
        $whazzup = $whazzup->get_whazzup();
        return $whazzup;
    }

    public function VerrifOnlineServeur()
    {
        $whazzup = new whazzupController();
        $whazzup = $whazzup->getwhazzupbdd();
        $review = Storage::get('public/whazzup/' . $whazzup['whazzup'] . '.json');
        $json = json_decode($review, true);
        $atcs = collect($json['clients']['atcs']);
        $pilots = collect($json['clients']['pilots']);
        /** search the informations */
        $atcs = $atcs->whereIn('userId', $this->vid);
        $pilots = $pilots->whereIn('userId', $this->vid);
        $atcs = $atcs->toArray();
        $pilots = $pilots->toArray();
        /** joker dans la recherhe index */
        $atcs = array_values($atcs) ?? null;
        $pilots = array_values($pilots) ?? null;
        $u = collect(["atc" => $atcs, "pilot" => $pilots]);
        return $u;
    }

    public function getVerrifOnlineServeur()
    {
        $q = $this->VerrifOnlineServeur();
        $whazzup = new whazzupController();
        if ($q['atc'] != null) {
            $ivao_session = $whazzup->track_session_id($q['atc'][0]['id']);
            $ivao_session_decode = json_decode($ivao_session, true);
            $time = Carbon::parse($ivao_session_decode["time"])->format('H:i');
            $new_icao = $ivao_session_decode['callsign'];
            $new_icao = explode("_", $new_icao);
            $new_icao = $new_icao[0];
            $r = $whazzup->get_rwy($new_icao);
            $metar = $whazzup->Get_metar($new_icao);
            $taf = $whazzup->Get_taf($new_icao);
            $atc_online = $whazzup->ckeck_online_atc($new_icao);
            $atis = $q['atc'][0]['atis']['lines'] ?? null;
            $callsign = $q['atc'][0]['callsign'];
            $callsign = explode("_", $callsign);
            $callsign = $callsign[0];
            $atc = [
                "callsign" => $ivao_session_decode['callsign'],
                "id_session" => $ivao_session_decode['id'],
                "frequency" => $ivao_session_decode["atcSession"]['frequency'],
                "rating" =>$ivao_session_decode["user"]['rating']["atcRating"]["shortName"],
                "time" => $time,
                "revision" => $q['atc'][0]['atis']['revision'],
                "atis" => $r,
                "metar" => $metar['metar'],
                "taf" => $taf['taf'],
            ];
            $metarController = new metarController();
            $plateform = $atc_online;
            $pilots = new PilotIvaoController();
            $pilots = $pilots->getAirplaneToPilots($callsign);
                
            $fly = [
                "fly" => [
                    "departure" => [
                        "count" => $pilots["departure"]["count"],
                        "data" => $pilots["departure"]["data"]
                    ],
                    "arrivals" => [
                        "count" => $pilots["arrivals"]["count"],
                        "data" => $pilots["arrivals"]["data"]
                    ]
                ]
            ];
            
            return view("myoline.atc", ["atc" => $atc, "atis" => $atis, "plateform" => $plateform, "fly" => $fly]);
        }
        elseif ($q['pilot'] != null) {
            $ivao_session = $whazzup->track_session_id($q['pilot'][0]['id']);
            $ivao_session_decode = json_decode($ivao_session, true);
            $fp_session = $whazzup->get_flightPlans($ivao_session_decode["id"]);
            $fp_session = $fp_session[0];
            //dd($fp_session);
            $atc_online_departure = $whazzup->ckeck_online_atc($fp_session['departureId']);
            $atc_online_arrival = $whazzup->ckeck_online_atc($fp_session['arrivalId']);
            $q = $q['pilot'];
            
            $distance_arrival = $q[0]['lastTrack']['arrivalDistance'] ?? null;
            $distance_arrival = explode(".", $distance_arrival);
            $speed = $q[0]['lastTrack']['groundSpeed'] / 60 ?? 1;
            $metar_dep = $whazzup->Get_metar($fp_session["departureId"]);
            $metar_arr = $whazzup->Get_metar($fp_session["arrivalId"]);
            $taf_dep = $whazzup->Get_taf($fp_session["departureId"]);
            $taf_arr = $whazzup->Get_taf($fp_session["arrivalId"]);

            if ($speed <= 0) {
                $speed = 1;
            }
            if ($distance_arrival <= 0) {
                $distance_arrival[0] = 1;
            }
            $arrival_time = $distance_arrival[0] / $speed;
            $arrival_time = Carbon::now()->addMinutes($arrival_time)->format('H:i');

            $p = [
                "callsign" => $ivao_session_decode['callsign'],
                "id_session" => $ivao_session_decode['id'],
                "lastTrack" => [
                    "altitude" => $q[0]['lastTrack']['altitude'],
                    "transponder" => $q[0]['lastTrack']['transponder'],
                    "arrivalDistance" => $distance_arrival[0],
                    "state" => $q[0]['lastTrack']['state'],
                    "time" => Carbon::parse($ivao_session_decode["time"])->format('H:i'),
                ],
                "flightPlan" => [
                    "aircraftId" => $fp_session['aircraftId'],
                    "departureId" => $fp_session['departureId'],
                    "arrivalId" => $fp_session['arrivalId'],
                    "alternateId" => $fp_session['alternativeId'],
                    "route" => $fp_session['route'],
                    "speed" => $fp_session['speed'],
                    "level" => $fp_session['level'],
                    "flightRules" => $fp_session['flightRules'],
                    "flightType" => $fp_session['flightType'],
                    "personsOnBoard" => $fp_session['peopleOnBoard'],
                    "departureTime" => Carbon::parse($fp_session['departureTime'])->format('H:i'),
                    "aircraftEquipments" => $q[0]['flightPlan']['aircraftEquipments'],
                    "aircraftTransponderTypes" => $q[0]['flightPlan']['aircraftTransponderTypes']
                ],
                "arrival_time" => $arrival_time,
                "distance_arrival" => $distance_arrival[0],
                "aircraft" => $q[0]['flightPlan']['aircraft']['model'],
                "wakeTurbulence" => $q[0]['flightPlan']['aircraft']['wakeTurbulence']
            ];
            $atc = [
                "depature" => [
                    $atc_online_departure
                ],
                "arrival" => [
                    $atc_online_arrival
                ]
            ];
            $metar = [
                "departure" => [
                    "metar" => $metar_dep['metar'],
                    "taf" => $taf_dep['taf']
                ],
                "arrival" => [
                    "metar" => $metar_arr['metar'],
                    "taf" => $taf_arr['taf']
                ]
            ];
            $chartController = new chartController();
            $chart = [
                "departure" => [
                    "IFR" => $chartController->chartIFR($p["flightPlan"]["departureId"]),
                    "VFR" => $chartController->chartVFR($p["flightPlan"]["departureId"]),
                ],
                "arrival" => [
                    "IFR" => $chartController->chartIFR($p["flightPlan"]["arrivalId"]),
                    "VFR" => $chartController->chartVFR($p["flightPlan"]["arrivalId"]),
                ]
            ];

            return view("myoline.pilot", ["pilot" => $p, "atc" => $atc, "chart" => $chart, "metar" => $metar]);
        } else {
            return redirect()->route("home");
        }
    }

    public function check_online()
    {
        $q = $this->VerrifOnlineServeur();

        if($q["atc"] != null){
            return true;
        }
        elseif($q["pilot"] != null){
            return true;
        }
        else{
            return false;
        }
    }
}
