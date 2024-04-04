<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\whazzupController;
use Illuminate\Support\Facades\Http;

class EventIvaoController extends Controller
{

    protected $event;

    public function __construct()
    {    
     $sr_env = new whazzupController();
     $event = $sr_env->event_ivao();
     $this->event = $event;
     return $this->event;       
    }

    public function get_event_ivao_world(){
        $eventIvaoWorld = [];
        for ($i=0; $i < count($this->event) ; $i++) { 
            $eventIvaoWorld[$i]['startDate'] = date('Y-m-d H:i:s', strtotime($this->event[$i]['startDate']));
            $eventIvaoWorld[$i]['endDate'] = date('Y-m-d H:i:s', strtotime($this->event[$i]['endDate']));
            $eventIvaoWorld[$i]['title'] = $this->event[$i]['title'];
            $eventIvaoWorld[$i]['imageUrl'] = $this->event[$i]['imageUrl'];
            $eventIvaoWorld[$i]['description'] = $this->event[$i]['description'];
            $eventIvaoWorld[$i]['infoUrl'] = $this->event[$i]['infoUrl'];
            $eventIvaoWorld[$i]['divisions'] = $this->event[$i]['divisions'];
            $eventIvaoWorld[$i]['airports'] = $this->event[$i]['airports'];

        }

        return $eventIvaoWorld;
        
    }

    public function get_event_ivao_RFE_RFO(){
       
        $rfe = $this->get_event_ivao_world();
        $regexpr = '/RFE|RFO/';
        $rfe_rfo = [];
        for ($i=0; $i < count($rfe) ; $i++) { 
            if(preg_match($regexpr, $rfe[$i]['title'])){
                $rfe_rfo[$i]['startDate'] = $rfe[$i]['startDate'];
                $rfe_rfo[$i]['endDate'] = $rfe[$i]['endDate'];
                $rfe_rfo[$i]['title'] = $rfe[$i]['title'];
                $rfe_rfo[$i]['imageUrl'] = $rfe[$i]['imageUrl'];
                $rfe_rfo[$i]['description'] = $rfe[$i]['description'];
                $rfe_rfo[$i]['infoUrl'] = $rfe[$i]['infoUrl'];
                $rfe_rfo[$i]['divisions'] = $rfe[$i]['divisions'];
                $rfe_rfo[$i]['airports'] = $rfe[$i]['airports'];
            }
        }
        $newrfe_rfo = array_values($rfe_rfo);
        return $newrfe_rfo;
       
        
    }

    public function get_event_ivao_FR_days(){
       
        $date = date("c");
        $mois = date('m');
        $year = date('Y');
        $date_day = date('d/m/Y');
        $event_fr = [];
        $envent_fr_search = Http::get("https://www.ivao.fr/fr/api/p/calendar/".$year."-".$mois.".json");
        $event_fr = $envent_fr_search->json();
        $event_day = [];
            try {
                foreach ($event_fr[$date_day] as $key => $value) {
                $envent_day["type"] = $value['type'];
                $envent_day["name"] = $value['name'];
                $envent_day["started_at"] = date('Y-m-d H:i:s', strtotime($value['started_at']));
                $envent_day["description"] = $value['tooltip_fr'] ?? "Pas de description disponible";
                array_push($event_day, $envent_day);
            }
        } catch (\Throwable $th) {
            $event_day = [];
        }
       
        return $event_day;       
    }


    public function get_event_ivao_FR_tomorrow(){
       
        $date = date("c");
        $mois = date('m');
        $year = date('Y');
        $date_day = date('d/m/Y');
        $event_fr = [];
        $envent_fr_search = Http::get("https://www.ivao.fr/fr/api/p/calendar/".$year."-".$mois.".json");
        $event_fr = $envent_fr_search->json();
        $event_tomorrow = [];
        $tomorrow = date('d/m/Y', strtotime('+1 day'));
        if(!isset($event_fr[$tomorrow])){
            $tomorrow = date('d/m/Y', strtotime('+2 day'));
        }
        if(!isset($event_fr[$tomorrow])){
            $tomorrow = date('d/m/Y', strtotime('+3 day'));
        }
        foreach ($event_fr[$tomorrow] as $key => $value) {
            $envent_tomorrow["type"] = $value['type'];
            $envent_tomorrow["name"] = $value['name'];
            $envent_tomorrow["started_at"] = date('Y-m-d H:i:s', strtotime($value['started_at']));
            $envent_tomorrow["description"] = $value['tooltip_fr'] ?? "Pas de description disponible";
            array_push($event_tomorrow, $envent_tomorrow);
        }
        return $event_tomorrow;
    }

    public function get_event_ivao_FR(){
        $eventIvaoFR = [];
        $eventIvaoFR = [
            'today' => $this->get_event_ivao_FR_days(),
            'tomorrow' => $this->get_event_ivao_FR_tomorrow(),
        ];
        return $eventIvaoFR;
    }


}