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
            $eventIvaoWorld[$i]['startDate'] = date('Y-m-d H:i:s', strtotime($this->event[$i]['startDate'])) ?? NULL;
            $eventIvaoWorld[$i]['endDate'] = date('Y-m-d H:i:s', strtotime($this->event[$i]['endDate'])) ?? NULL;
            $eventIvaoWorld[$i]['title'] = $this->event[$i]['title'] ?? NULL;
            $eventIvaoWorld[$i]['imageUrl'] = $this->event[$i]['imageUrl'] ?? NULL;
            $eventIvaoWorld[$i]['description'] = $this->event[$i]['description'] ?? NULL;
            $eventIvaoWorld[$i]['infoUrl'] = $this->event[$i]['infoUrl'] ?? NULL;
            $eventIvaoWorld[$i]['divisions'] = $this->event[$i]['divisions'] ?? NULL;
            $eventIvaoWorld[$i]['airports'] = $this->event[$i]['airports'] ?? NULL;

        }

        return $eventIvaoWorld;
        
    }

    public function get_event_ivao_RFE_RFO(){
       
        $rfe = $this->get_event_ivao_world();
        $regexpr = '/RFE|RFO/';
        $rfe_rfo = [];
        for ($i=0; $i < count($rfe) ; $i++) { 
            if(preg_match($regexpr, $rfe[$i]['title'])){
                $rfe_rfo[$i]['startDate'] = $rfe[$i]['startDate'] ?? NULL;
                $rfe_rfo[$i]['endDate'] = $rfe[$i]['endDate'] ?? NULL;
                $rfe_rfo[$i]['title'] = $rfe[$i]['title'] ?? NULL;
                $rfe_rfo[$i]['imageUrl'] = $rfe[$i]['imageUrl'] ?? NULL;
                $rfe_rfo[$i]['description'] = $rfe[$i]['description'] ?? NULL;
                $rfe_rfo[$i]['infoUrl'] = $rfe[$i]['infoUrl'] ?? NULL;
                $rfe_rfo[$i]['divisions'] = $rfe[$i]['divisions'] ?? NULL;
                $rfe_rfo[$i]['airports'] = $rfe[$i]['airports'] ?? NULL;
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
            $event_day = NULL;
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
            'today' => $this->get_event_ivao_FR_days() ?? NULL,
            'tomorrow' => $this->get_event_ivao_FR_tomorrow() ?? NULL,
        ];
        return $eventIvaoFR;
    }


}