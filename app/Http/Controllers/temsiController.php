<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class temsiController extends Controller
{
    //https://aviation.meteo.fr/affiche_image.php?time=1691227758&type=sigwx/fr/france&date=20230805090000&mode=img
    //https://aviation.meteo.fr/affiche_image.php?time=1691229945&type=sigwx/fr/france&date=20230805100000&mode=img&comment=
    //https://aviation.meteo.fr/affiche_image.php?time=1691228397&type=wintemp/fr/france/fl020&date=20230805090000&mode=img&comment=

    public function timestamp()
    {
        $date = date("Y-m-d H:i:s");
        $date = strtotime($date);
        return $date;
    }

    public function config_Date()
    {
        $date = new DateTime();

        $date = $date->format('Ymdh' . '0000');
        return $date;
    }

    public function logique_time()
    {
        $date = new DateTime();
        $dates = date("H");
        // 3h 6h 9h 12h 15h 18h 21h 00h
        if ($dates >= 0 && $dates < 3) {
            $dates = "00";
        } elseif ($dates >= 3 && $dates < 6) {
            $dates = "03";
        } elseif ($dates >= 6 && $dates < 9) {
            $dates = "06";
        } elseif ($dates >= 9 && $dates < 12) {
            $dates = "09";
        } elseif ($dates >= 12 && $dates < 15) {
            $dates = "12";
        } elseif ($dates >= 15 && $dates < 18) {
            $dates = "15";
        } elseif ($dates >= 18 && $dates < 21) {
            $dates = "18";
        } elseif ($dates >= 21 && $dates < 24) {
            $dates = "21";
        }
        $news_date = $dates;
        $date = $date->format('Ymd' . $news_date . "0000");
        return $date;
    }

    public function link_temsi()
    {

        $link = "https://aviation.meteo.fr/affiche_image.php?time=" . $this->timestamp() . "&type=sigwx/fr/france&date=" . $this->logique_time() . "&mode=img&comment=";
        return $link;
    }

    public function link_wintemp()
    {

        $link = "https://aviation.meteo.fr/affiche_image.php?time=" . $this->timestamp() . "&type=wintemp/fr/france/fl020&date=" . $this->logique_time() . "&mode=img&comment=";
        return $link;
    }

    public function all_chart()
    {
        $link = [
            "sigwx" => $this->link_temsi(),
            "wintemp" => $this->link_wintemp()
        ];
        return $link;
    }
}
