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

    public function checkdate(){
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        if($date <= $this->config_Date()){
            $date = date("m")-1;
            if($date < 10){
                $date = "0".$date;
            }
            $date = $this->Airac_date($date);
        }else{
            $date = date("m");
        }
        return $date;
        
    }

    public function DateAirac()
    {
        $date = $this->checkdate();
        $date = $this->Airac_date($date);
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
            "13" => "2023-12-23",
            "01" => "2024-01-25",
            "02" => "2024-02-22",
            "03" => "2024-03-21",
            "04" => "2024-04-16",
            "05" => "2024-05-16",
            "06" => "2024-06-13",
            "07" => "2024-07-11",
            "08" => "2024-08-08",
            "09" => "2024-09-05",
            "10" => "2024-10-03",
            "11" => "2024-10-31",
            "12" => "2024-11-28",
            "13" => "2024-12-26",
        ];
        $date = $airac_table[$airac];
        return $date;
    }
}
