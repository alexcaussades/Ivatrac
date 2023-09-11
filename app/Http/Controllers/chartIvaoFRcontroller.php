<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class chartIvaoFRcontroller extends Controller
{
    public function strcture($ivao){
        $ccr = substr($ivao, 0, -1);
        $ccr_search = str_split($ccr);
        $new_icao = $ccr_search[0].$ccr_search[1].$ccr_search[2].$ccr_search[2];
        $new_icao = strtolower($new_icao);      
        
        $r =[
            "new_icao" => $new_icao,
            "ivao" => $ivao,
        ];
        return $r;
    }

    public function chart_ivao($ivao){
        $structure = $this->strcture($ivao);
        $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_AD/Fiche_".$structure["ivao"].".pdf";
        $response = Http::get($url);
        if ($response->status() == 200) {
            return $url;
        }else{
            return null;
        }
    }

    public function structure_CCR($ccr){
        $ccr_search = str_split($ccr);
        $new_icao = $ccr_search[0].$ccr_search[1].$ccr_search[2].$ccr_search[2];
        $new_icao = strtolower($new_icao);      
        
        $r =[
            "new_icao" => $new_icao,
            "ccr" => $ccr,
        ];
        return $r;
    }

    public function chart_ccr($ccr){
        $structure = $this->structure_CCR($ccr);
        //dd($structure);
        $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_".$structure["ccr"].".xlsx";
        $response = Http::get($url);
        if ($response->status() == 200) {
            return $url;
        }else{
            return null;
        }
    }
}
