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
        $icaoUpper = strtoupper($new_icao);     
        
        $r =[
            "new_icao" => $new_icao,
            "ccr" => $icaoUpper,
        ];
        return $r;
    }

    public function chart_ccr($ccr){
        $structure = $this->structure_CCR($ccr);
        $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_".$structure["ccr"].".xlsx";
        //https://storage.ivao.fr/lfrr_public/Memo_CCR/LFRR_CTR.xlsx
        switch ($structure["ccr"]) {
            case 'LFBB':
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_LFBB_CTR.xlsx";
                break;
            case 'LFEE':
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_LFEE.xlsx";
                break;
            case 'LFFF':
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_LFFF.xlsx";
                break;
            case 'LFRR':
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_LFRR_CTR.xlsx";
                break;
            default:
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Fiche_CCR/Memo_".$structure["ccr"].".xlsx";
                break;
        }
        if($structure["ccr"] == "LFMM"){
            $data = [
                "LFMM_NW" => "https://storage.ivao.fr/lfmm_public/Memo_CCR/Memo_LFMM_NW.xlsx",
                "LFMM_S" => "https://storage.ivao.fr/lfmm_public/Memo_CCR/Memo_LFMM_S.xlsx",
                "count"=> 2,
            ];
            return $data;
        }
        $response = Http::get($url);
        if ($response->status() == 200) {
            return $url;
        }else{
            return null;
        }
    }
}
