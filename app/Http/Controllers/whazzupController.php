<?php

namespace App\Http\Controllers;

use App\Models\whazzupdd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TestRunner\TestResult\Collector;

class whazzupController extends Controller
{
    public function getwhazzup()
    {
        $api = $this->store_Whazzup();
        $whazzup = json_decode($api[0], true);
        return $whazzup;
    }

    public function donwload_whazzup()
    {
        $whazzup = Http::get('https://api.ivao.aero/v2/tracker/whazzup');
        return $whazzup;
    }

    public function store_file_whazzup()
    {
        $wha = $this->donwload_whazzup();
        $date = json_decode($wha);
        $name = Str::random(15);
        $sto = Storage::put('public/whazzup/' . $name . '.json', $wha);
        $sto = Storage::url('public/whazzup/' . $name . '.json', $wha);
        //$review = Storage::get('public/whazzup/'.$name.'.json');
        $r = [
            "name" => $name,
            "date" => $date->updatedAt,
            "url" => $sto
        ];
        //$a = json_decode($r["review"]);
        return $r;
    }

    public function store_Whazzup()
    {
        $datenow = date('Y-m-d H:i:s');
        $date = $this->getwhazzupbdd()->whazzup_date;
        // si la date de la bdd est superieur a la date actuelle
        if ($date > $datenow) {
            $whazzupbdd = $this->getwhazzupbdd();
            $review = Storage::get('public/whazzup/' . $whazzupbdd['whazzup'] . '.json');
            $whazzup = collect($review);
            return $whazzup;
        } else {
            $whazzup = $this->store_file_whazzup();
            $whazzup_date = $whazzup["date"];
            $whazzup_date = $this->add_date($whazzup_date);
            $whazzupbdd = new whazzupdd();
            $whazzupbdd->whazzup = $whazzup["name"];
            $whazzupbdd->whazzup_date = $whazzup_date;
            $whazzupbdd->save();
            return $this->store_Whazzup();
        }
    }

    public function add_date($value)
    {
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

    public function connexion()
    {
        $api = $this->getwhazzup();
        return $api["connections"];
    }

    public function bddid()
    {
        $whazzupbdd = whazzupdd::all();
        $whazzupbdd = $whazzupbdd->last();
        return $whazzupbdd;
    }

    public function Heurechange()
    {
        $whazzupbdd = whazzupdd::all();
        $whazzupbdd = $whazzupbdd->last();
        $whazzupbdd = $whazzupbdd->whazzup_date;
        $whazzupbdd = Date::createFromFormat('Y-m-d H:i:s', $whazzupbdd);
        $whazzupbdd = $whazzupbdd->format('H:i');
        return $whazzupbdd;
    }
}
