<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class chartIvaoFRcontroller extends Controller
{
    public function strcture($ivao){
        $whazzup = new whazzupController();
        $prepare_icao = $whazzup->get_airport($ivao);
        $new_icao = strtolower($prepare_icao["centerId"]);      
        
        $r =[
            "new_icao" => $new_icao,
            "ivao" => $ivao,
            "info" => $prepare_icao,
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
        $whazzup = new whazzupController();
        $prepare_icao = $whazzup->get_center($ccr);
        $new_icao = strtolower($prepare_icao["id"]);
        $icaoUpper = strtoupper($prepare_icao["id"]);     
        
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
        $data = null;
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
                $url = "https://storage.ivao.fr/". $structure["new_icao"] ."_public/Memo_CCR/LFRR_CTR.xlsx";
                break;
            case 'LFMM':
                $data = [
                    "LFMM_NW" => "https://storage.ivao.fr/lfmm_public/Memo_CCR/Memo_LFMM_NW.xlsx",
                    "LFMM_S" => "https://storage.ivao.fr/lfmm_public/Memo_CCR/Memo_LFMM_S.xlsx",
                ];
            default:
                $url = null;
                break;
        }
        //dd($url, $data);
        if($data != null){
            return $data;
        }
        if($url != null){
            return $url;
        }
    }
}
