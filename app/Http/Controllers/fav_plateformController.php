<?php

namespace App\Http\Controllers;

use App\Models\favPlateform;
use Illuminate\Http\Request;

class fav_plateformController extends Controller
{
    public $icao_plateform;
    public $id_user;


    /**
     * Constructeur de la class avec id user et icao plateform
     */

    public function __construct($id_user, $icao_plateform = null)
    {
        $this->icao_plateform = $icao_plateform;
        $this->id_user = $id_user;
    }
    

    public function getPlateformAllUser()
    {
        $plateform = favPlateform::all();
        $plateform_array = [];
        foreach ($plateform as $key => $value) {
            $plateform_array[] = $value->icao_plateform;
        }
        return $plateform_array;
    }

    
    /** Créer un tableau pour la recherche des plateforms  */

    public function getPlateform()
    {
        $plateform = favPlateform::where("id_user", $this->id_user)->get();
        $plateform_array = [];
        foreach ($plateform as $key => $value) {
            $plateform_array[] = $value->icao_plateform;
        }
        return $plateform_array;
    }

    public function addPlateform()
    {
        $plateform = new favPlateform();
        $plateform->icao_plateform = $this->icao_plateform;
        $plateform->id_user = $this->id_user;
        $plateform->save();
    }
    
    public function deletePlateform()
    {
        $plateform = favPlateform::where("icao_plateform", $this->icao_plateform)->where("id_user", $this->id_user)->first();
        $plateform->delete();
    }

    public function getPlateformAll()
    {
        $plateform = favPlateform::where("id_user", $this->id_user)->get();
        $plateform_array = [];
        foreach ($plateform as $key => $value) {
            $plateform_array[] = $value->icao_plateform;
        }
        return $plateform_array;
    }
}
