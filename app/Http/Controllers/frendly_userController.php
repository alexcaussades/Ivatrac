<?php

namespace App\Http\Controllers;

use App\Models\whazzupdd;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\friendlyUserModel;
use Illuminate\Support\Facades\Storage;

class frendly_userController extends Controller
{
    public $vid_friend;
    public $name_friend;
    public $id_user;


    public function __construct($id_user, $vid_friend=null, $name_friend=null)
    {
        $this->vid_friend = $vid_friend ?? null;
        $this->name_friend = $name_friend ?? null;
        $this->id_user = $id_user;
    }

    public function getFrendlyUser()
    {
        $frendly_user = friendlyUserModel::where("id_user", $this->id_user)->get();
        $frendly_user_array = [];
        foreach ($frendly_user as $key => $value) {
            $frendly_user_array[] = [
                "id" => $value->id,
                "vid_friend" => $value->vid_friend,
                "name_friend" => $value->name_friend];
        }
        return $frendly_user_array;
    }

    public function addFrendlyUser()
    {
        $frendly_user = new friendlyUserModel();
        $frendly_user->vid_friend = $this->vid_friend;
        $frendly_user->name_friend = $this->name_friend;
        $frendly_user->id_user = $this->id_user;
        $frendly_user->save();
    }

    public function deleteFrendlyUser($id)
    {        
        /** suprimer une entrée dans la bdd*/
        $frendly_user = friendlyUserModel::where("id", $id)->first();
        $frendly_user->delete();
    }

    public function updateFrendlyUser($id)
    {
        $frendly_user = friendlyUserModel::where("id", $id)->first();
        $frendly_user->vid_friend = $this->vid_friend;
        $frendly_user->name_friend = $this->name_friend;
        $frendly_user->save();
    }

    public function getFrendlyUserAll()
    {
        $frendly_user = friendlyUserModel::all();
        $frendly_user_array = [];
        foreach ($frendly_user as $key => $value) {
            $frendly_user_array[] = $value->vid_friend . " " . $value->name_friend;
        }
        return $frendly_user_array;
    }

    /** verification online via le file whazzup dans la bdd avec ouverture de fichier */

    public function verification_friend(){
        $frendly_user = friendlyUserModel::where("id_user", $this->id_user)->get();
        $frendly_user_array = [];
        foreach ($frendly_user as $key => $value) {
            $frendly_user_array[] = $value->vid_friend;
        }
        $whazzup = new whazzupController();
        $whazzup = $whazzup->getwhazzupbdd();
        $review = Storage::get('public/whazzup/' . $whazzup['whazzup'] . '.json');
        $json = json_decode($review, true);
        $atcs = collect($json['clients']['atcs']);
        $pilots = collect($json['clients']['pilots']);
        /** search the informations */
        $atcs = $atcs->whereIn('userId', $frendly_user_array);
        $pilots = $pilots->whereIn('userId', $frendly_user_array);
        $atcs = $atcs->toArray();
        $pilots = $pilots->toArray();
        /** joker dans la recherhe index */
        $atcs = array_values($atcs);
        $pilots = array_values($pilots);
        $u = collect(["atc" => $atcs, "pilot" => $pilots]);
        return $u;
    }

    public function count_verification(){
        $r = $this->verification_friend();
        $atc = count($r['atc']);
        $pilot = count($r['pilot']);
        $count = $atc + $pilot;
        return $count;
    }

    public function count_verification_API(){
        $r = $this->verification_friend();
        $atc = count($r['atc']);
        $pilot = count($r['pilot']);
        $count = $atc + $pilot;
        $count = [
            "atc" => $atc,
            "pilot" => $pilot,
            "total" => $count
        ];
        return $count;
    }

    public function get_friens_online(){
        $r = $this->verification_friend();
        $getpilots = new PilotIvaoController();
        $atc = $r['atc'];
        $pilot = $r['pilot'];
        $count = $this->count_verification();
        $frendly_user_array = [];
        foreach ($atc as $key => $value) {
            $frendly_user_array[] = [
                "callsign" => $value['callsign'],
                $plit = explode("_", $value['callsign']),
                "name" => friendlyUserModel::where("vid_friend", $value['userId'])->first()->name_friend ?? null,
                "type" => "ATC",
                "VID" => $value['userId'],
                "time" => Carbon::parse($atc[$key]["lastTrack"]["time"])->format('H:i'),
                "info" => "DEP: ". count($getpilots->getApideparturePilot($plit[0]))." ARR: ".count($getpilots->getApiArrivalPilot($plit[0]))
            ];
        }
        foreach ($pilot as $key => $value) {
            $frendly_user_array[] = [
                "callsign" => $value['callsign'],
                "name" => friendlyUserModel::where("vid_friend", $value['userId'])->first()->name_friend ?? null,
                "type" => "Pilot",
                "VID" => $value['userId'],
                "time" => Carbon::parse($pilot[$key]["lastTrack"]["time"])->format('H:i'),
                "info" => $pilot[$key]["flightPlan"]["departureId"]." - " . $pilot[$key]["flightPlan"]["arrivalId"]." (". $pilot[$key]["lastTrack"]["state"].")"
            ];
        }
        return $frendly_user_array;
    }

    public function get_friends_via_id($id){
        $frendly_user = friendlyUserModel::where("id", $id)->first();
        $frendly_user_array = [
            "id" => $frendly_user->id,
            "vid_friend" => $frendly_user->vid_friend,
            "name_friend" => $frendly_user->name_friend,
            "id_user" => $frendly_user->id_user
        ];
        return $frendly_user_array;
    }
}
