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

    public function whazzup()
    {
        $api = new whazzupController();
        $api = $api->getwhazzup();
        return $api;
    }

    /** pour la recherche des positions de controle */
    public function getFirAtc($icao)
    {
        $api = $this->whazzup();
        $newrecherche = str_split($icao);
        $newfinal = [$newrecherche[0], $newrecherche[1], $newrecherche[2]];
        $newfinal = implode("", $newfinal);
        $newfinal = strtoupper($newfinal);
        $regex = $newfinal ."[A-Z]{1,}_[A-Za-z]{3,}";
        $p = [];
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/".$regex."/", $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    $p[] = [$value];
                }
            }
        }
        return $p;
    }

    /** pour la recherche des centres de controle */
    public function getFirCTR($icao)
    {
        $api = $this->whazzup();
        $newrecherche = str_split($icao);
        $newfinal = [$newrecherche[0], $newrecherche[1]];
        $newfinal = implode("", $newfinal);
        $newfinal = strtoupper($newfinal);
        $regex = $newfinal ."[A-Z]{1,}_[A-Z]{1,}_CTR";
        $regex2 = $newfinal ."[A-Z]{1,}_CTR";
        $p = [];
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/".$regex."/", $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    $p[] = [$value];
                }
            }
        }
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/".$regex2."/", $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    $p[] = [$value];
                }
            }
        }
        return $p;
    }

    /** pour la recherche des APP */
    public function getApiATC_APP($icao)
    {
        $api = $this->whazzup();
        $regex = "/" . $icao . "_[A-Za-z0-9]+_APP/";
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all($regex, $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    return $value;
                }
            }
            if ($value["callsign"] == $icao . "_APP") {
                return $value;
            }
        }
    }

    /** pour la recherche des TWR */
    public function getApiATC_TWR($icao)
    {
        $api = $this->whazzup();
        $regex = "/" . $icao . "_[A-Za-z0-9]+_TWR/";
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all($regex, $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    return $value;
                }
            }
            if ($value["callsign"] == $icao . "_TWR") {
                return $value;
            }
        }
    }

    /** pour la recherche des TWR */
    public function getApiATC_GND($icao)
    {
        $api = $this->whazzup();
        $regex = "/" . $icao . "_[A-Za-z0-9]+_GND/";
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all($regex, $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    return $value;
                }
            }
            if ($value["callsign"] == $icao . "_GND") {
                return $value;
            }
        }
    }

    /** pour la recherche des FSS */
    public function getApiATC_FSS($icao)
    {
        $api = $this->whazzup();
        $regex = "/" . $icao . "_[A-Za-z0-9]+_FSS/";
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all($regex, $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    return $value;
                }
            }
            if ($value["callsign"] == $icao . "_FSS") {
                return $value;
            }
        }
    }

    public function getATC($icao)
    {
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
        $secret = env("METAR_API_KEY");
        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Authorization' => 'Bearer ' . $secret,
        ])
            ->get('https://avwx.rest/api/metar/' . $icao);

        $time = $response->json("time")["dt"] ?? null;
        $i = new UtilsDateTime($time);
        //dd($response);
        if ($response->json("wind_speed") == null) {
            $speed = "None";
        } else {
            $speed = $response->json("wind_speed")["value"] * 1.852;
            $speed = round($speed, 0);
        }


        $r = [
            'metar' => $response->json("raw"),
            'station' => $response->json("station"),
            'visibility' => $response->json("visibility")["repr"] ?? "None",
            'flight_rules' => $response->json("flight_rules") ?? "None",
            "QNH" => $response->json("altimeter")["repr"] ?? "None",
            "wind" => [
                "wind" => $response->json("wind")["repr"] ?? "None",
                "direction" => $response->json("wind_direction")["repr"] ?? "None",
                "wind_variable" => $response->json("wind_variable_direction")[0]["repr"] ?? "None",
                "speed_KT" => $response->json("wind_speed")["repr"] ?? "None",
                "speed_KM" => $speed,
            ],
            "temperature" => $response->json("temperature")["repr"] ?? "None",
            "dewpoint" => $response->json("dewpoint")["repr"] ?? "None",
            "clouds" => $response->json("clouds")[0]["repr"] ?? "None",
            "meta_day" => [
                "time" => $i->format("H:i T"),
                "date" => $i->format("d/m/Y"),
                "day" => $i->format("l"),
            ],
            "remarks" => $response->json("remarks") ?? "None",
        ];


        return $r;
    }

    public function taf($icao)
    {
        /** secret github chifrée sur la repo L10 */
        $secret = env("METAR_API_KEY");

        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Authorization' => 'Bearer ' . $secret,
        ])
            ->get('https://avwx.rest/api/taf/' . $icao);

        $r = [
            'taf' => $response->json("raw"),
            'station' => $response->json("station"),
            'time' => $response->json("time")["repr"] ?? "None",
            'forecast' => $response->json("forecast")[0]["flight_rules"] ?? "None",
        ];


        return $r;
    }
}
