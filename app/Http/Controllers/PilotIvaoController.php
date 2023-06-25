<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PilotIvaoController extends Controller
{
    public function getApideparturePilot($icao)
    {
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        $count = $api->json()["connections"]["pilot"];
        $pilot = $api->json()["clients"]["pilots"];
        $pilotDeparture = [];
        for ($i = 0; $i <= $count; $i++) {
            foreach ($pilot as $key => $value) {
                if ($value["flightPlan"]["departureId"] == $icao) {
                    array_push($pilotDeparture, $value);
                }
            }
            return $pilotDeparture;
        }
    }

    public function getApiArrivalPilot($icao)
    {
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        $count = $api->json()["connections"]["pilot"];
        $pilot = $api->json()["clients"]["pilots"];
        $PilotArrival = [];
        for ($i = 0; $i <= $count; $i++) {
            foreach ($pilot as $key => $value) {
                if ($value["flightPlan"]["arrivalId"] == $icao) {
                    array_push($PilotArrival, $value);
                }
            }
            return $PilotArrival;
        }
    }

    public function getAirplaneToPilots($icao)
    {
        $departure = $this->getApideparturePilot($icao);
        $arrivals = $this->getApiArrivalPilot($icao);
        $coutDeparture = count($departure);
        $coutArrivals = count($arrivals);
        $r = [
            "icao" => $icao,
            "departure" => [
                "count" => $coutDeparture,
                "data" => $departure
            ],
            "arrivals" => [
                "count" => $coutArrivals,
                "data" => $arrivals
            ]
        ];
        return $r;
    }
}
