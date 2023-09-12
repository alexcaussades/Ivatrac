<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class CarteSIAController extends Controller
{
    public function config_Date()
    {
        $date = date("m");
        $date = $this->Airac_date($date);
        return $date;
    }
    public function DateAirac()
    {
        $date = $this->config_Date();
        $date = new DateTime($date);
        $date = $date->format('d_M_Y');
        $date = strtoupper($date);
        return $date;
    }


    public function chartIFR($icao)
    {
        $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_" . $this->DateAirac() . "/FRANCE/AIRAC-" . $this->config_Date() . "/html/eAIP/FR-AD-2." . $icao . "-fr-FR.html";
        $header = get_headers($chart);
        if ($header[0] == "HTTP/1.1 404 Not Found") {
            $chart = null;
        }
        if ($header[0] == "HTTP/1.1 200 OK") {
            $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_" . $this->DateAirac() . "/FRANCE/AIRAC-" . $this->config_Date() . "/html/eAIP/FR-AD-2." . $icao . "-fr-FR.html";
        }

        return $chart;
    }


    public function chartVFR($icao)
    {
        $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_" . $this->DateAirac() . "/Atlas-VAC/PDF_AIPparSSection/VAC/AD/AD-2." . $icao . ".pdf";
        $header = get_headers($chart);
        if ($header[0] == "HTTP/1.1 404 Not Found") {
            $chart = null;
        }
        if ($header[0] == "HTTP/1.1 200 OK") {
            $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_" . $this->DateAirac() . "/Atlas-VAC/PDF_AIPparSSection/VAC/AD/AD-2." . $icao . ".pdf";
        }
        return $chart;
    }

    public function Airac_date($value)
    {
        $airac = $value;
        $airac_table = [
            "07" => "2023-07-13",
            "08" => "2023-08-10",
            "09" => "2023-09-07",
            "10" => "2023-10-05",
            "11" => "2023-11-02",
            "12" => "2023-12-28",
        ];
        $date = $airac_table[$airac];
        return $date;
    }
}
