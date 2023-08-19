<?php

namespace App\Http\Controllers;

use App\Models\whazzupdd;
use Illuminate\Http\Request;
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
            $frendly_user_array[] = $value->vid_friend;
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

    public function deleteFrendlyUser()
    {
        $frendly_user = friendlyUserModel::where("vid_friend", $this->vid_friend)->where("id_user", $this->id_user)->first();
        $frendly_user->delete();
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
        $frendly_user = friendlyUserModel::all();
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
        $u = collect(["atcs" => $atcs, "pilots" => $pilots]);
        return $u["pilots"][0]["callsign"];
           
    }
}
