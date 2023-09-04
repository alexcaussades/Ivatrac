<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class temsiController extends Controller
{
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
        $date = $date->format('Ymd' . $news_date . "00");
        return $date;
    }

    public function logique_time_temsi()
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
        $date = $date->format('Ymd' . $news_date . "00");
        return $date;
    }

    public function link_temsi_fr()
    {

        // https://aerometeo.fr/data/weather/temsi_france_202309042100.pdf
        $link = "https://aerometeo.fr/data/weather/temsi_france_" . $this->logique_time_temsi() . ".pdf";
        return $link;
    }

    public function link_wintemp_fr()
    {

        // https://aerometeo.fr/data/weather/wintem_france_202309042100.pdf
        $link = "https://aerometeo.fr/data/weather/wintem_france_" . $this->logique_time() . ".pdf";
        return $link;
    }

    public function link_temsi_eu()
    {

        //https://aerometeo.fr/data/weather/temsi_france_202308310900.pdf
        $link = "https://aerometeo.fr/data/weather/temsi_euroc_" . $this->logique_time_temsi() . ".pdf";
        return $link;
    }

    public function link_wintemp_eu()
    {

        //https://aerometeo.fr/data/weather/wintem_france_202308310900.pdf
        $link = "https://aerometeo.fr/data/weather/wintem_euroc_" . $this->logique_time() . ".pdf";
        return $link;
    }

    public function get_temsi(){
        /** mettre les entetes du PDF pour le lire */
        return redirect($this->link_temsi_fr());  
    }

    public function get_wintemp(){
        /** mettre les entetes du PDF pour le lire */
        return redirect($this->link_wintemp_fr());  
    }

    public function all_chart()
    {
        $link = [
            "FR" => [
                "sigwx" => $this->link_temsi_fr(),
                "wintemp" => $this->link_wintemp_fr()
            ],
            "EU" => [
                "sigwx" => $this->link_temsi_eu(),
                "wintemp" => $this->link_wintemp_eu()
            ],
        ];
        return $link;
    }
}
