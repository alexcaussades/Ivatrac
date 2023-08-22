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

        if ($q['atc'] != null) {
            $time = Carbon::parse($q['atc'][0]['lastTrack']["time"])->format('H:i');
            $atis = count($q['atc'][0]['atis']['lines']);
            $callsign = $q['atc'][0]['callsign'];
            $callsign = explode("_", $callsign);
            $callsign = $callsign[0];
            $atc = [
                "callsign" => $q['atc'][0]['callsign'],
                "id_session" => $q['atc'][0]['id'],
                "frequency" => $q['atc'][0]["atcSession"]['frequency'],
                "rating" => $q['atc'][0]['rating'],
                "time" => $time,
                "revision" => $q['atc'][0]['atis']['revision'],
                "atis" => $q['atc'][0]['atis']['lines'],
            ];
            $metarController = new metarController();
            $plateform = [
                "plateform" => [
                    "APP" => $metarController->getApiATC_APP($callsign),
                    "TWR" => $metarController->getApiATC_TWR($callsign),
                    "GND" => $metarController->getApiATC_GND($callsign),
                    "FSS" => $metarController->getApiATC_FSS($callsign),
                ],
            ];
                $pilots = new PilotIvaoController();
                $pilots = $pilots->getAirplaneToPilots($callsign);
                
            $fly = [
            
            ];
            return view("myoline.atc", ["atc" => $atc, "atis" => $atis, "plateform" => $plateform, "fly" => $fly]);
        }
        elseif ($q['pilot'] != null) {
            $q = $q['pilot'];
            $distance_arrival = $q[0]['lastTrack']['arrivalDistance'];
            $distance_arrival = explode(".", $distance_arrival);
            $speed = $q[0]['lastTrack']['groundSpeed'] / 60;
            if ($speed <= 0) {
                $speed = 1;
            }
            if ($distance_arrival <= 0) {
                $distance_arrival[0] = 1;
            }
            $arrival_time = $distance_arrival[0] / $speed;
            $arrival_time = Carbon::now()->addMinutes($arrival_time)->format('H:i');

            $p = [
                "callsign" => $q[0]['callsign'],
                "id_session" => $q[0]['id'],
                "lastTrack" => [
                    "altitude" => $q[0]['lastTrack']['altitude'],
                    "transponder" => $q[0]['lastTrack']['transponder'],
                    "arrivalDistance" => $distance_arrival[0],
                    "state" => $q[0]['lastTrack']['state'],
                    "time" => Carbon::parse($q[0]["lastTrack"]["time"])->format('H:i'),
                ],
                "flightPlan" => [
                    "aircraftId" => $q[0]['flightPlan']['aircraftId'],
                    "departureId" => $q[0]['flightPlan']['departureId'],
                    "arrivalId" => $q[0]['flightPlan']['arrivalId'],
                    "alternateId" => $q[0]['flightPlan']['alternativeId'],
                    "route" => $q[0]['flightPlan']['route'],
                    "speed" => $q[0]['flightPlan']['speed'],
                    "level" => $q[0]['flightPlan']['level'],
                    "flightRules" => $q[0]['flightPlan']['flightRules'],
                    "flightType" => $q[0]['flightPlan']['flightType'],
                    "personsOnBoard" => $q[0]['flightPlan']['peopleOnBoard'],
                    "departureTime" => Carbon::parse($q[0]['flightPlan']['departureTime'])->format('H:i'),
                    "aircraftEquipments" => $q[0]['flightPlan']['aircraftEquipments'],
                    "aircraftTransponderTypes" => $q[0]['flightPlan']['aircraftTransponderTypes']
                ],
                "arrival_time" => $arrival_time,
                "distance_arrival" => $distance_arrival[0],
                "aircraft" => $q[0]['flightPlan']['aircraft']['model'],
                "wakeTurbulence" => $q[0]['flightPlan']['aircraft']['wakeTurbulence']
            ];
            $metarController = new metarController();
            //$departure = $metarController->metar($p["flightPlan"]["departureId"]);
            //$arrival = $metarController->metar($p["flightPlan"]["arrivalId"]);
            $atc = [
                "depature" => [
                    "APP" => $metarController->getApiATC_APP($p["flightPlan"]["departureId"]),
                    "TWR" => $metarController->getApiATC_TWR($p["flightPlan"]["departureId"]),
                    "GND" => $metarController->getApiATC_GND($p["flightPlan"]["departureId"]),
                    "FSS" => $metarController->getApiATC_FSS($p["flightPlan"]["departureId"]),
                ],
                "arrival" => [
                    "APP" => $metarController->getApiATC_APP($p["flightPlan"]["arrivalId"]),
                    "TWR" => $metarController->getApiATC_TWR($p["flightPlan"]["arrivalId"]),
                    "GND" => $metarController->getApiATC_GND($p["flightPlan"]["arrivalId"]),
                    "FSS" => $metarController->getApiATC_FSS($p["flightPlan"]["arrivalId"]),
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

            return view("myoline.pilot", ["pilot" => $p, "atc" => $atc, "chart" => $chart]);
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
