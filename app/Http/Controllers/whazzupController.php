<?php

namespace App\Http\Controllers;

use App\Models\whazzupdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class whazzupController extends Controller
{
    public function getwhazzup()
    {
        $api = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        return $api;
        
    }

    public function donwload_whazzup(){
        $whazzup = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        return $whazzup;
    }
    
    public function store_Whazzup(){
       if($this->getwhazzupbdd() == null){
            $whazzup = $this->donwload_whazzup();
            $whazzup_date = $whazzup["updatedAt"];
            $whazzup = json_encode($whazzup);
            $whazzup_date = $this->add_date($whazzup_date);
            $whazzupbdd = new whazzupdd();
            $whazzupbdd->whazzup = $whazzup;
            $whazzupbdd->whazzup_date = $whazzup_date;
            $whazzupbdd->save();
            return $whazzupbdd;
       }

       $datenow = date('Y-m-d H:i:s');
       $date = $this->getwhazzupbdd()->whazzup_date;
        // si la date de la bdd est superieur a la date actuelle
        if($date > $datenow){
            $whazzupbdd = $this->getwhazzupbdd();
            $whazzup = $whazzupbdd->whazzup;
            $whazzup = json_decode($whazzup);
            return $whazzup;
        }else{
            $whazzup = $this->donwload_whazzup();
            $whazzup_date = $whazzup["updatedAt"];
            $whazzup = $whazzup;
            $whazzup_date = $this->add_date($whazzup_date);
            $whazzupbdd = new whazzupdd();
            $whazzupbdd->whazzup = $whazzup;
            $whazzupbdd->whazzup_date = $whazzup_date;
            $whazzupbdd->save();
            return $whazzupbdd;
        }
    }

    public function add_date($value){
        $dateIn = $value;
        $datenew = date('Y-m-d H:i:s', strtotime($dateIn));
        // ajout de 5 minutes a la nouvelle date
        $date = Date::createFromFormat('Y-m-d H:i:s', $datenew);
        $date = $date->addMinutes(5);
        $date = $date->format('Y-m-d H:i:s');
        $a =  $date;
        return $a;
    }

    public function getwhazzupbdd()
    {
        $whazzupbdd = whazzupdd::all();
        $whazzupbdd = $whazzupbdd->last();
        return $whazzupbdd;
    }
}
