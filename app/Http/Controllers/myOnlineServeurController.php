<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\metarController;
use App\Http\Controllers\PilotIvaoController;
use App\Http\Controllers\whazzupController;
use App\Http\Controllers\chartIvaoFRcontroller;
use App\Http\Controllers\CarteSIAController;



class myOnlineServeurController extends Controller
{
    public $vid;
    public $option;

    public function __construct($vid = null)
    {
        $this->vid = $vid ?? null;
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
        $whazzupp = new whazzupController();
        $chartIvaoFRcontroller = new chartIvaoFRcontroller();
        $chartController = new CarteSIAController();
        if ($q['atc'] != null) {

            if ($q['atc'][0]['atcSession']['position'] == "CTR") {
                $ivao_session = $whazzupp->track_session_id($q['atc'][0]['id']);
                $ivao_session_decode = json_decode($ivao_session, true);
                $time = Carbon::parse($ivao_session_decode["time"])->format('H:i');
                $metar = new metarController();
                $ident = $q['atc'][0]['callsign'];
                $ident = explode("_", $ident);
                $ident[0] = substr($ident[0], 0, -1);
                $metar = $metar->getFirAtc($ident[0]);
                $chart_crr = $chartIvaoFRcontroller->chart_ccr($ident[0]);             
                $atc_online = [];
                for ($i = 0; $i < count($metar); $i++) {
                    $atc_online[$i]["icao"] = $metar[$i][0]["callsign"];
                    $atc_online[$i]["icao"] = explode("_", $atc_online[$i]["icao"]);
                    $atc_online[$i]["icao"] = $atc_online[$i]["icao"][0];
                    $atc_online[$i]["callsign"] = $metar[$i][0]["callsign"];
                    $atc_online[$i]["chart_ivao"] = $chartIvaoFRcontroller->chart_ivao($atc_online[$i]["icao"]);
                    $atc_online[$i]["frequency"] = $metar[$i][0]["atcSession"]["frequency"];
                    $atc_online[$i]["time"] = Carbon::parse($metar[$i][0]["time"])->format('H:i');
                    $atc_online[$i]["metar"] = $whazzupp->Get_metar($atc_online[$i]["icao"])->json();
                    $atc_online[$i]["metar"] = $atc_online[$i]["metar"]["metar"] ?? null;
                    $atc_online[$i]["taf"] = $whazzupp->Get_taf($atc_online[$i]["icao"])->json();
                    $atc_online[$i]["revision"] = $metar[$i][0]["atis"]["revision"];
                }

                $atc = [
                    "callsign" => $ivao_session_decode['callsign'],
                    "id_session" => $ivao_session_decode['id'],
                    "frequency" => $ivao_session_decode["atcSession"]['frequency'],
                    "rating" => $ivao_session_decode["user"]['rating']["atcRating"]["shortName"],
                    "time" => $time,
                    "revision" => $q['atc'][0]['atis']['revision'],
                ];
                return view("myoline.ccr", ["atc" => $atc, "atc_online" => $atc_online, "chart_crr" => $chart_crr]);

            }
            $ivao_session = $whazzupp->track_session_id($q['atc'][0]['id']);
            $ivao_session_decode = json_decode($ivao_session, true);
            $time = Carbon::parse($ivao_session_decode["time"])->format('H:i');
            $new_icao = $ivao_session_decode['callsign'];
            $new_icao = explode("_", $new_icao);
            $new_icao = $new_icao[0];
            $r = $whazzupp->get_rwy($new_icao);
            $metar = $whazzupp->Get_metar($new_icao);
            $taf = $whazzupp->Get_taf($new_icao);
            $atc_online = $whazzupp->ckeck_online_atc($new_icao);
            $chart_ivao = $chartIvaoFRcontroller->chart_ivao($new_icao);
            $atis = $q['atc'][0]['atis']['lines'] ?? null;
            $callsign = $q['atc'][0]['callsign'];
            $callsign = explode("_", $callsign);
            $callsign = $callsign[0];
            $atc = [
                "callsign" => $ivao_session_decode['callsign'],
                "id_session" => $ivao_session_decode['id'],
                "frequency" => $ivao_session_decode["atcSession"]['frequency'],
                "rating" => $ivao_session_decode["user"]['rating']["atcRating"]["shortName"],
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
            return view("myoline.atc", ["atc" => $atc, "atis" => $atis, "plateform" => $plateform, "fly" => $fly, "chart_ivao" => $chart_ivao]);
        
        } elseif ($q['pilot'] != null) {
            $ivao_session = $whazzupp->track_session_id($q['pilot'][0]['id']);
            $ivao_session_decode = json_decode($ivao_session, true);
            $fp_session = $whazzupp->get_flightPlans($ivao_session_decode["id"]);
            $fp_session = $fp_session[0];
            //dd($fp_session);
            $atc_online_departure = $whazzupp->ckeck_online_atc($fp_session['departureId']);
            $atc_online_arrival = $whazzupp->ckeck_online_atc($fp_session['arrivalId']);
            $q = $q['pilot'];

            $distance_arrival = $q[0]['lastTrack']['arrivalDistance'] ?? null;
            $distance_arrival = explode(".", $distance_arrival);
            $speed = $q[0]['lastTrack']['groundSpeed'] / 60 ?? 1;
            $metar_dep = $whazzupp->Get_metar($fp_session["departureId"]);
            $metar_arr = $whazzupp->Get_metar($fp_session["arrivalId"]);
            $taf_dep = $whazzupp->Get_taf($fp_session["departureId"]);
            $taf_arr = $whazzupp->Get_taf($fp_session["arrivalId"]);


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

        if ($q["atc"] != null) {
            return true;
        } elseif ($q["pilot"] != null) {
            return true;
        } else {
            return false;
        }
    }

}
