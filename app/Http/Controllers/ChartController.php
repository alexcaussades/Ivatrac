<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    Public function config_Date(){
        $date = "2023-06-15";
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

    
    public function chartIFR($icao){
        $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_".$this->DateAirac()."/FRANCE/AIRAC-".$this->config_Date()."/html/eAIP/FR-AD-2.".$icao."-fr-FR.html";
        $header = get_headers($chart);
        if($header[0] == "HTTP/1.1 404 Not Found"){
            $chart = "#";
        }
        if($header[0] == "HTTP/1.1 200 OK"){
            $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_".$this->DateAirac()."/FRANCE/AIRAC-".$this->config_Date()."/html/eAIP/FR-AD-2.".$icao."-fr-FR.html";
        }

        return $chart;
    }
    

    Public function chartVFR($icao)
    {
        $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_".$this->DateAirac()."/Atlas-VAC/PDF_AIPparSSection/VAC/AD/AD-2.".$icao.".pdf";
        $header = get_headers($chart);
        if($header[0] == "HTTP/1.1 404 Not Found"){
            $chart = "#";
        }
        if($header[0] == "HTTP/1.1 200 OK"){
            $chart = "https://www.sia.aviation-civile.gouv.fr/dvd/eAIP_".$this->DateAirac()."/Atlas-VAC/PDF_AIPparSSection/VAC/AD/AD-2.".$icao.".pdf";
        }
        return $chart;
    }
}
