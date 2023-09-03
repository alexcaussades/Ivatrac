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
        $regex = $newfinal . "[A-Z]{1,}_[A-Za-z]{3,}";
        $p = [];
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/" . $regex . "/", $value["callsign"], $matches)) {
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
        $regex = $newfinal . "[A-Z]{1,}_[A-Z]{1,}_CTR";
        $regex2 = $newfinal . "[A-Z]{1,}_CTR";
        $p = [];
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/" . $regex . "/", $value["callsign"], $matches)) {
                if ($value["callsign"] == $matches[0][0]) {
                    $p[] = [$value];
                }
            }
        }
        foreach ($api["clients"]["atcs"] as $key => $value) {
            if (preg_match_all("/" . $regex2 . "/", $value["callsign"], $matches)) {
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

    public function visibility($metar)
    {
        $regex_visibility_vertical = "/[0-9]{4}|CAVOK/";
        preg_match_all($regex_visibility_vertical, $metar, $matches);
        return $matches[0][2];
    }

    public function winds($metar)
    {
        $regext = "/[0-9]{2}KT/";
        //KT
        preg_match($regext, $metar, $match);
        $wingt = $match[0];
        $ex = explode("KT", $wingt);
        $winds_Default = $ex[0];
        $wingt = $ex[0];
        $wingt = $wingt * 1.852;
        $wingt = round($wingt, 0);
        //direction du vent dans le metar
        $regexwings = "/[0-9]{5}KT/";
        preg_match($regexwings, $metar, $matchyy);
        $ex = str_split($matchyy[0]);
        $form =  $ex[0] . "" . $ex[1] . "" . $ex[2];
        // variation du vent
        $regexvariable = "/[0-9]{3}V[0-9]{3}/";
        preg_match($regexvariable, $metar, $matchvariable);
        if ($matchvariable == null) {
            $matchvariable[0] = "0V0";
        }
        $ex = explode("V", $matchvariable[0]);
        $variable = $ex[0] . "/" . $ex[1];
        $r = [
            "variable" => $variable,
            "direction" => $form,
            "winds" => $winds_Default,
            "winds_KM" => $wingt,
        ];
        return $r;
    }

    public function clouds($metar)
    {
        $regexclouds = "/(BKN|FEW|SCT|OVC)([0-9]{3})(CB|TCU)?/";
        preg_match_all($regexclouds, $metar, $clouds);
        // tout remetre dans une ligne avec une virgule
        if ($clouds[0] != Null) {
            if (count($clouds[0]) > 1) {
                $clouds = implode(", ", $clouds[0]);
            } else {
                $clouds = $clouds[0];
            }
        } else {
            $clouds = "No";
        }
        return $clouds;
    }

    public function temp_qnh($metar)
    {
        $regexTemparature = "/[0-9]{2}\/[0-9]{2}/";
        $regexQNH = "/[Q][0-9]{4}/";
        preg_match($regexTemparature, $metar, $temparature);
        preg_match($regexQNH, $metar, $QNH);
        $ex = explode("/", $temparature[0]);
        $r = [
            "temp" => [
                "temp" => $ex[0],
                "dewpoint" => $ex[1],
            ],
            "qnh" => $QNH[0] ?? "0",
        ];
        return $r;
    }

    public function station($metar)
    {
        $regexstation = "/[A-Z]{4}/";
        preg_match($regexstation, $metar, $station);
        return $station[0];
    }

    public function time($metar)
    {
        $timeregex = "/(\d{2})(\d{2})(\d{2})Z/";
        preg_match($timeregex, $metar, $time);
        $time = $time[2] . ":" . $time[3];
        return $time;
    }

    public function metar($icao)
    {
        $whazzup = new whazzupController();
        $metar = $whazzup->Get_metar($icao);
        $metar = json_decode($metar);
        $temp = $this->temp_qnh($metar->metar);
        $winds = $this->winds($metar->metar);
        $visibility = $this->visibility($metar->metar);
        $clouds = $this->clouds($metar->metar);
        $station = $this->station($metar->metar);
        $times = $this->time($metar->metar);


        $r = [
            'metar' => $metar->metar,
            'station' => $station,
            'visibility' => $visibility ?? "None",
            'flight_rules' => "None",
            "QNH" => $temp["qnh"] ?? "None",
            "wind" => [
                "wind" => $winds["direction"] ?? "None",
                "direction" => $winds["direction"] ?? "None",
                "wind_variable" => $winds["variable"] ?? "None",
                "speed_KT" => $winds["winds"] ?? "None",
                "speed_KM" => $winds["winds_KM"],
            ],
            "temperature" => $temp["temp"]["temp"] ?? "None",
            "dewpoint" => $temp["temp"]["dewpoint"] ?? "None",
            "clouds" => $clouds ?? "None",
            "meta_day" => [
                "time" => $times. " Z"
            ],
        ];


        return $r;
    }

    public function taf($icao)
    {
        $whazzup = new whazzupController();
        $taf = $whazzup->Get_taf($icao);
        $taf = json_decode($taf);
        $taf = [
            "taf" => $taf->taf,
        ];
        return $taf;
    }
}
