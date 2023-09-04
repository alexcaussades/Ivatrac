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

    public function get_token()
    {
        $openid_url = 'https://api.ivao.aero/.well-known/openid-configuration';
        $openid_url = 'https://api.ivao.aero/.well-known/openid-configuration';
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === FALSE) {
            /* Handle error */
            die('Error while getting openid data');
        }
        $openid_data = json_decode($openid_result, true);
        $idclient = env("ivao_api_client_id");
        $secret = env("ivao_api_client_secret");
        $token_req_data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $idclient,
            'client_secret' => $secret,
            'scope' => 'tracker'
        );

        // use key 'http' even if you send the request to https://...
        $token_options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($token_req_data)
            )
        );
        $token_context  = stream_context_create($token_options);
        $token_result = file_get_contents($openid_data['token_endpoint'], false, $token_context);
        if ($token_result === FALSE) {
            /* Handle error */
            die('Error while getting token');
        }
        $token_res_data = json_decode($token_result, true);
        $access_token = $token_res_data['access_token']; // Here is the access token
        return $access_token;
    }

    public function get_session()
    {
        $openid_url = 'https://api.ivao.aero/.well-known/openid-configuration';
        $openid_url = 'https://api.ivao.aero/.well-known/openid-configuration';
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === FALSE) {
            /* Handle error */
            die('Error while getting openid data');
        }
        $openid_data = json_decode($openid_result, true);
        $idclient = env("ivao_api_client_id");
        $secret = env("ivao_api_client_secret");
        $token_req_data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $idclient,
            'client_secret' => $secret,
            'scope' => "friends:read"
        );

        // use key 'http' even if you send the request to https://...
        $token_options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($token_req_data)
            )
        );
        $token_context  = stream_context_create($token_options);
        $token_result = file_get_contents($openid_data['token_endpoint'], false, $token_context);
        if ($token_result === FALSE) {
            /* Handle error */
            die('Error while getting token');
        }
        $token_res_data = json_decode($token_result, true);
        $access_token = $token_res_data['access_token']; // Here is the access token
        return $access_token;
    }

    public function API_request_session($path = null, $method = 'GET', $data = null, $headers = null)
    {
        $url = 'https://api.ivao.aero/' . $path;
        $headers = [
            'Authorization' => 'Bearer ' . $this->get_session(),
            'Accept'        => 'application/json',
        ];
        $response = Http::withHeaders($headers)->get($url);
        return $response;
    }

    public function API_request($path = null, $method = 'GET', $data = null, $headers = null)
    {
        $url = 'https://api.ivao.aero/' . $path;
        $headers = [
            'Authorization' => 'Bearer ' . $this->get_token(),
            'Accept'        => 'application/json',
        ];
        $response = Http::withHeaders($headers)->get($url);
        return $response;
    }

    public function Get_metar($icao=null){
        $metar = $this->API_request("v2/airports/".$icao."/metar");
        return $metar;
    }

    public function Get_taf($icao=null){
        $metar = $this->API_request("v2/airports/".$icao."/taf");
        return $metar;
    }

    public function Get_Position($icao=null){
        $metar = $this->API_request("v2/tracker/now/atc");
        return $metar;
    }

    public function track_session_id($idsession = null){        
        $metar = $this->API_request("v2/tracker/sessions/" . $idsession);
        return $metar;
    }
}
