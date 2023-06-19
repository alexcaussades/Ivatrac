<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Nette\Utils\DateTime as UtilsDateTime;

class metarController extends Controller
{
    //secret key github

    public function index()
    {
        return view('metar');
    }

  
    public function getApiATC_APP($icao){
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        foreach ($api->json()["clients"]["atcs"] as $key => $value) {
            if($value["callsign"] == $icao."_APP"){
                return $value;
            }
        }
    }

    public function getApiATC_TWR($icao){
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');

        foreach ($api->json()["clients"]["atcs"] as $key => $value) {
            
            if($value["callsign"] == $icao."_TWR"){
                return $value;
            }
        } 
    }

    public function getApiATC_GND($icao){
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        foreach ($api->json()["clients"]["atcs"] as $key => $value) {
            
            if($value["callsign"] == $icao."_GND"){
                return $value;
            }
        }  
    }

    public function getApiATC_FSS($icao){
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        foreach ($api->json()["clients"]["atcs"] as $key => $value) {
            
            if($value["callsign"] == $icao."_FSS"){
                return $value;
            }
        }  
    }

    public function getATC($icao){
        $APP = $this->getApiATC_APP($icao);
        $TWR = $this->getApiATC_TWR($icao);
        $GND = $this->getApiATC_GND($icao);
        $FSS = $this->getApiATC_FSS($icao);

        $r = [
            "APP" => $APP,
            "TWR" => $TWR,
            "GND" => $GND,
            "FSS" => $FSS,
        ];

        return $r;
    }

    public function metar($icao)
    {
        /** secret github chifrée sur la repo L10 */
        $secret = env("METAR_API_KEY");

        $response = Http::withHeaders([ 
            'Accept'=> '*/*',  
            'Authorization'=> 'Bearer '.$secret, 
        ]) 
        ->get('https://avwx.rest/api/metar/'.$icao);
        
        $time = $response->json("time")["dt"] ?? null;
        $i = new UtilsDateTime($time);

        $speed = $response->json("wind_speed")["value"] * 1.852;
        $speed = round($speed, 0);
    
        $r = [
            'metar' => $response->json("raw"),
            'station' => $response->json("station"),
            'visibility' => $response->json("visibility")["repr"],
            'flight_rules' => $response->json("flight_rules"),
            "QNH" => $response->json("altimeter")["repr"],
            "wind" => [
                "wind" => $response->json("wind")["repr"] ?? null,
                "direction" => $response->json("wind_direction")["repr"],
                "wind_variable" => $response->json("wind_variable_direction")[0]["repr"] ?? null,
                "speed_KT" => $response->json("wind_speed")["repr"],
                "speed_KM" => $speed,
            ],
            "temperature" => $response->json("temperature")["repr"],
            "dewpoint" => $response->json("dewpoint")["repr"],
            "clouds" => $response->json("clouds")[0]["repr"] ?? null,
            "meta_day" => [
                "time" => $i->format("H:i T"),
                "date" => $i->format("d/m/Y"),
                "day" => $i->format("l"),],
            "remarks" => $response->json("remarks") ?? null,
        ];

        return $r;
    }

    public function taf($icao)
    {
        /** secret github chifrée sur la repo L10 */
        $secret = env("METAR_API_KEY");

        $response = Http::withHeaders([ 
            'Accept'=> '*/*',  
            'Authorization'=> 'Bearer '.$secret, 
        ]) 
        ->get('https://avwx.rest/api/taf/'.$icao); 
        
        $r = [
            'taf' => $response->json("raw"),
            'station' => $response->json("station"),
            'time' => $response->json("time")["repr"],
            'forecast' => $response->json("forecast")[0]["flight_rules"],
        ];
       
        
        return $r;
    }
}
