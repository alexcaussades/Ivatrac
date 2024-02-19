<?php

namespace App\Http\Controllers;

use App\Models\whazzupdd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthIVAOController;
use PHPUnit\TestRunner\TestResult\Collector;

class whazzupController extends Controller
{
    public function getwhazzup()
    {
        $api = $this->donwload_whazzup();
        $whazzup = $api;
        return $whazzup;
    }

    public function donwload_whazzup()
    {
        $whazzup = $this->whazzup_api_traker();
        return $whazzup;
    }

    public function store_file_whazzup()
    {
        $wha = $this->donwload_whazzup();
        $date = $wha["updatedAt"];
        $wha = json_encode($wha);
        $name = Str::random(15);
        $sto = Storage::put('public/whazzup/' . $name . '.json', $wha);
        $sto = Storage::url('public/whazzup/' . $name . '.json', $wha);
        //$review = Storage::get('public/whazzup/'.$name.'.json');
        $r = [
            "name" => $name,
            "date" => $date,
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
        $api = $this->whazzup_api_traker();
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

        $openid_result = file_get_contents($openid_url, false);

        if ($openid_result === FALSE) {
            /* Handle error */
            die('Error while getting openid data');
        }

        $openid_data = json_decode($openid_result, true);
        //dd($openid_data);

        $idclient = env("ivao_api_client_id");
        $secret = env("ivao_api_client_secret");
        $state = rand(100000, 999999);

        $token_req_data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $idclient,
            'client_secret' => $secret,
            'scope' => 'tracker',
            'state' => $state
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

        // if ($token_result === FALSE) {
        //     /* Handle error */
        //     die('Error while getting token');
        // }

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
            'scope' => "friends:read friends:write tracker"
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

    public function API_Delect_session($path = null, $method = 'GET', $data = null, $headers = null)

    {
        $url = 'https://api.ivao.aero/' . $path;
        if (session("ivao_tokens")) {

            $json = session("ivao_tokens");
            $access_token = $json["access_token"];
            $headers = [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept'        => 'application/json',
            ];
        } else {
            $headers = [
                'Authorization' => 'Bearer ' . $this->get_token(),
                'Accept'        => 'application/json',
            ];
        }
        $response = Http::withHeaders($headers)->delete($url);
        return $response;
    }

    public function API_request($path = null, $method = 'GET', $data = null, $headers = null)
    {
        $url = 'https://api.ivao.aero/' . $path;
        if (session("ivao_tokens")) {
            $headers = [
                'Authorization' => 'Bearer ' . session("ivao_tokens")["access_token"],
                'Accept'        => 'application/json',
            ];
        } else {
            $headers = [
                'Authorization' => 'Bearer ' . $this->get_token(),
                'Accept'        => 'application/json',
            ];
        }

        $response = Http::withHeaders($headers)->get($url);
        return $response;
    }

    public function refresh_token_api_ivao(Request $request)
    {
        $auth = new AuthIVAOController();
        $json = $auth->sso($request);
        $json = json_decode($json);
        $json = $json->access_token;
        return $json;
    }

    public function API_POST($path = null, $method = 'POST', $data = null, $headers = null)
    {
        $json = session("ivao_tokens");
        $access_token = $json["access_token"];
        $url = 'https://api.ivao.aero/' . $path;
        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
            'Accept'        => 'application/json',
        ];
        $response = Http::withHeaders($headers)->post($url, $data);
        return $response;
    }

    public function Get_metar($icao = null)
    {
        $metar = $this->API_request("v2/airports/" . $icao . "/metar");
        return $metar;
    }

    public function Get_taf($icao = null)
    {
        $metar = $this->API_request("v2/airports/" . $icao . "/taf");
        return $metar;
    }

    public function Get_Position($vid)

    {
        $metar = $this->API_request("v2/tracker/now/atc");
        $p = json_decode($metar, true);
        //in list of pilot search userId 
        foreach ($p as $pilot) {
            if ($pilot["userId"] == $vid) {
                $data = $pilot;
                return $data;
            }
        }
    }

    public function Get_Position_old()
    {
        $metar = $this->API_request("v2/tracker/now/atc");
        return $metar;
    }

    public function Get_Position_pilote($vid)
    {
        $metar = $this->API_request("v2/tracker/now/pilots");
        $p = json_decode($metar, true);
        //in list of pilot search userId 
        foreach ($p as $pilot) {
            if ($pilot["userId"] == $vid) {
                $data = $pilot;
                return $data;
            }
        }
    }

    public function track_session_id($idsession = null)
    {
        $metar = $this->API_request("v2/tracker/sessions/" . $idsession);
        return $metar;
    }

    public function track_session($idsession = null)
    {
        $metar = $this->API_request("/v2/airports/LFBL/squawks");
        return $metar;
    }
    public function online_me()
    {
        $online = $this->API_request("/v2/users/me/sessions/now");
        return $online;
    }

    public function user_me()
    {
        $users_me = $this->API_request("/v2/users/me");
        $users_me = $users_me->json();
        $users_me = collect($users_me);
        $users_me = $users_me->toArray();


        /** convertir timestant uniquement en heure en addition des jours */
        $heure = Carbon::createFromTimestamp(0)->format('Y-m-d H:i:s');
        $atc = Carbon::createFromTimestamp($users_me["hours"][0]["hours"])->format('Y-m-d H:i');
        /** diff entre heure et minutes $atc */
        $atc1 = Carbon::parse($atc);
        $heure = Carbon::parse($heure);
        $atc = $atc1->diffInHours($heure);
        $atc = $atc1->diffInMinutes($heure) / 60;
        /** Rounded heure ATC */
        $atc = round($atc, 2);

        /** Pilot */
        $pilot = Carbon::createFromTimestamp($users_me["hours"][1]["hours"])->format('Y-m-d H:i');
        $pilot1 = Carbon::parse($pilot);
        $pilot = $pilot1->diffInHours($heure);
        $pilot = $pilot1->diffInMinutes($heure) / 60;
        /** Rounded heure Pilot */
        $pilot = round($pilot, 2);

        /** Staff */
        $staff = Carbon::createFromTimestamp($users_me["hours"][2]["hours"])->format('Y-m-d H:i');
        $staff1 = Carbon::parse($staff);
        $staff = $staff1->diffInHours($heure);
        $staff = $staff1->diffInMinutes($heure) / 60;
        /** Rounded heure Staff */
        $staff = round($staff, 2);



        $users_me = [
            "Grade" => [
                "AtcRating" => $users_me["rating"]["atcRating"]["shortName"],
                "PilotRating" => $users_me["rating"]["pilotRating"]["shortName"],
            ],
            "Hours" => [
                /** conversion value timestamp en heure */

                "AtcHours" => $atc,
                "PilotHours" => $pilot,
                "StaffHours" => $staff,
                "TotalHours" => $atc + $pilot,


            ],
        ];
        return $users_me;
    }

    public function revoke_token()
    {
        $token = $this->API_POST("v2/oauth/token/revoke");
        return $token;
    }

    public function get_atis_lasted($session_ivao = null)
    {
        $atis = $this->API_request("v2/ATCPositions/" . $session_ivao);
        return $atis;
    }

    public function get_rwys($icao)
    {
        $rwy = $this->API_request("/v2/airports/" . $icao . "/runways");
        return $rwy;
    }

    public function get_traffics($icao = null)
    {
        $metar = $this->API_request("v2/airports/" . $icao . "/traffics");
        return $metar->json();
    }

    public function get_traffics_count($icao = null)
    {
        $metar = $this->API_request("v2/airports/" . $icao . "/traffics/count");
        return $metar->json();
    }

    public function get_atis_latest_2($icao = null)
    {
        $metar = $this->API_request("v2/ATCPositions/" . $icao . "/atis/latest");
        return $metar;
    }

    public function get_session_vid($vid = null)
    {
        $metar = $this->API_request("/v2/users/" . $vid);
        return $metar->json();
    }

    public function whazzup_api_traker()
    {
        $whazzup = $this->API_request("v2/tracker/whazzup");
        $whazzup = $whazzup->json();
        return $whazzup;
    }

    public function get_flightPlans($id)
    {
        $whazzup = $this->API_request("v2/tracker/sessions/" . $id . "/flightPlans");
        $whazzup = $whazzup->json();
        return $whazzup;
    }

    public function get_friends()
    {
        $whazzup = $this->API_request("v2/webeye/friends");
        $whazzup = $whazzup->json();
        return $whazzup;
    }

    public function get_friends_online()
    {
        $whazzup = $this->API_request("v2/webeye/friends/online");
        $whazzup = $whazzup->json();
        return $whazzup;
    }
    public function post_friends($myvid, $vid)
    {
        $whazzup = $this->API_POST("v2/webeye/friends/" . $vid);
        $whazzup = $whazzup->json();
        return $whazzup;
    }

    public function delete_friends($vid)
    {
        $whazzup = $this->API_Delect_session("v2/webeye/friends/" . $vid);
        $whazzup = $whazzup->json();
        return $whazzup;
    }

    public function position_search($icao = null)
    {
        $metar = $this->API_request("v2/positions/search?startsWith=" . $icao);
        $u = collect($metar->json());
        $id = [];
        // for ($i = 0; $i < count($u); $i++) {
        //     $id[$i] = $u[$i]["composePosition"];
        // }
        // cree un array avec les id des callsign
        foreach ($u as $key => $value) {
            $id[$key] = $value["composePosition"];
        }
        $id = collect($id);

        return $id;
    }

    public function ckeck_online_atc($icao)
    {
        $act_possition = $this->position_search($icao);
        $position = $this->Get_Position_old();
        $position = $position->json();
        $position = collect($position);
        $o = [];
        for ($i = 0; $i < count($position); $i++) {
            $o[$i] = $position[$i]["callsign"];
            $o = collect($o);
        }
        $o = $o->toArray();
        $o = array_values($o);
        $act_possition = $act_possition->toArray();
        $l = array_intersect($act_possition, $o);
        $l = array_values($l);
        //rechercher la direfrence entre les deux array
        $diff = array_diff($act_possition, $l);
        $diff = array_values($diff);
        $open_atc = [];

        for ($i = 0; $i < count($l); $i++) {

            $open_atc[$i] = $this->get_atis_lasted($l[$i]);
            $open_atc[$i] = $open_atc[$i]->json();
        }
        $open_atc = collect($open_atc);
        $r = [
            "atc_open" => $open_atc,
            "atc_close" => $diff
        ];
        return $r;
    }

    public function get_rwy($icao)
    {
        $rwy = $this->get_rwys($icao);
        $ry = [];
        for ($i = 0; $i < count($rwy->json()); $i++) {
            $ry[$i] = $rwy[$i]["runway"];
            $ry = collect($ry);
        }
        $atis = $this->API_request("v2/airports/" . $icao . "/atis");
        if ($atis->status() == 404) {
            return null;
        }
        $ry = $ry->toArray();
        /** rechercher dans l'atis les LES MOTS "ARR" */
        $atis = $atis->json();
        if ($atis == null) {
            return null;
        }
        $ARR_search = $atis[0]["lines"];
        $ARR_search = collect($ARR_search);
        $ARR_search = $ARR_search->filter(function ($value, $key) {
            return Str::contains($value, 'ARR');
        });
        $ARR_search = $ARR_search->toArray();
        $ARR_search = array_values($ARR_search);
        if ($ARR_search == null) {
            return null;
        }
        $ARR_search = $ARR_search[0];
        return $ARR_search;
    }

    public function Bookings()
    {
        $bookings = $this->API_request("/v2/atc/bookings/daily");
        $bookings = $bookings->json();
        $book = [];
        for ($i = 0; $i < count($bookings); $i++) {
            $book[$i]["id"] = $bookings[$i]["id"];
            $book[$i]["Start_time"] = Carbon::parse($bookings[$i]["startDate"])->format('H:i') . " Z";
            $book[$i]["End_time"] = Carbon::parse($bookings[$i]["endDate"])->format('H:i') . " Z";
            $book[$i]["voice"] = $bookings[$i]["voice"];
            $book[$i]["training"] = $bookings[$i]["training"];
            $book[$i]["airport"] = $bookings[$i]["atcPosition"];
            $book[$i]["user"] = [
                "vid" => $bookings[$i]["user"]["id"],
            ];
        }
        return $book;
    }

    public function get_bookings_for_event($airport = "LFBL")
    {
        $bookings = $this->API_request("/v2/atc/bookings/daily");
        $bookings = $bookings->json();
        $pattern = '/' . $airport . '(.*)/';
        $book = [];
        for ($i = 0; $i < count($bookings); $i++) {
            if (preg_match($pattern, $bookings[$i]["atcPosition"])) {
                $book[$i]["id"] = $bookings[$i]["id"];
                $book[$i]["Start_time"] = Carbon::parse($bookings[$i]["startDate"])->format('H:i') . " Z";
                $book[$i]["End_time"] = Carbon::parse($bookings[$i]["endDate"])->format('H:i') . " Z";
                $book[$i]["voice"] = $bookings[$i]["voice"];
                $book[$i]["training"] = $bookings[$i]["training"];
                $book[$i]["airport"] = $bookings[$i]["atcPosition"];
                $book[$i]["user"] = [
                    "vid" => $bookings[$i]["user"]["id"],
                ];
            }
        }
        return $book;
    }

    public function get_fp_me()
    {
        $fp = $this->API_request("/v2/users/me/flightPlans");
        $fp = $fp->json();
        return $fp;
    }


    public function get_fp($id)
    {
        $fp = $this->API_request("/v2/users/me/flightPlans/" . $id);
        $fp = $fp->json();
        return $fp;
    }


    public function creator()
    {
        $creator = $this->API_request_session("/v2/creators/665306");
        $creator = $creator->json();
        return $creator;
    }

    public function get_airport_atc($icao)
    {
        $airport = $this->API_request("/v2/airports/" . $icao . "/ATCPositions");
        $airport = $airport->json();
        return $airport;
    }

    public function get_airport($icao)
    {
        $airport = $this->API_request("/v2/airports/" . $icao);
        $airport = $airport->json();
        return $airport;
    }

    public function get_center($icao)
    {
        $airport = $this->API_request("/v2/centers/" . $icao . "/subcenters");
        $airport = $airport->json();
        return $airport;
    }


    public function get_aircrafts($icao_code)
    {
        $aircrafts = $this->API_request("/v2/aircrafts/" . $icao_code);
        $aircrafts = $aircrafts->json();
        return $aircrafts;
    }

    public function event_ivao()
    {
        $event = $this->API_request("/v1/events");
        $event = $event->json();
        return $event;
    }
}
