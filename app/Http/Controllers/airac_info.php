<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class airac_info extends Controller
{
    public function connectToDatabase()
    {
        $connection = DB::connection('airac');

        // Utilisez la connexion pour exécuter des requêtes sur la base de données

        // Exemple : récupérer tous les enregistrements de la table "users"
        return $connection;
    }

    public function get_approach($icao, $runway = NULL)
    {
        $connection = $this->connectToDatabase();

        if ($runway == NULL) {
            $approach = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "A")->pluck("fix_ident")->toArray();
        } else if ($runway != NULL) {
            $approach = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "A")->where("runway_name", $runway)->pluck("fix_ident")->toArray();
            if (count($approach) == null) {
                $approach = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "A")->pluck("fix_ident")->toArray();
            }
        }

        return $approach;
    }

    public function get_departure($icao, $runway = NULL)
    {
        $connection = $this->connectToDatabase();

        if ($runway == NULL) {
            $departure = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "D")->pluck("fix_ident")->toArray();
        } else if ($runway != NULL) {
            $departure = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "D")->where("runway_name", $runway)->pluck("fix_ident")->toArray();
            if (count($departure) == null) {
                $departure = $connection->table('approach')->where('airport_ident', $icao)->where("suffix", "D")->pluck("fix_ident")->toArray();
            }
        }

        return $departure;
    }

    public function get_ils_airport($icao)
    {
        $connection = $this->connectToDatabase();

        $ils = $connection->table('ils')->where('loc_airport_ident', $icao)->get();
        $LOC = [];
        $RNAV = [];
        if (count($ils) > 0) {
            foreach ($ils as $key => $value) {
                if ($value->name == NULL) {
                    $LOC[] = $value;
                } else if ($value->type != NULL) {
                    $RNAV[] = $value;
                }
            }
        }
        $LOC = collect($LOC)->sortBy('ils_id');
        $RNAV = collect($RNAV)->sortBy('ils_id');
        $Q = collect(["LOC" => $LOC, "RNAV" => $RNAV]);
        return $Q;
    }

    public function get_ils_information($icao, $runway = NULL)
    {
        $connection = $this->connectToDatabase();

        if ($runway == NULL) {
            $ils = $connection->table('ils')->where('loc_airport_ident', $icao)->get();
            foreach ($ils as $key => $value) {
                $frequency = $value->frequency;
                $reformattedNumber = substr_replace($frequency, ".", 3, 0) . " Mhz";
                $ils[$key]->frequency = $reformattedNumber;
            }
            $new_ils = [];
            for ($i = 0; $i < count($ils); $i++) {
                $new_ils[$i]["ident"] = $ils[$i]->ident;
                $new_ils[$i]["type"] = $ils[$i]->type;
                $new_ils[$i]["frequency"] = $ils[$i]->frequency;
                $new_ils[$i]["loc_runway_name"] = $ils[$i]->loc_runway_name;
                $new_ils[$i]["loc_heading"] = $ils[$i]->loc_heading;
                $new_ils[$i]["loc_heading"] = explode(".", $new_ils[$i]["loc_heading"])[0];
            }
            $ils = collect($new_ils);
        } else if ($runway != NULL) {
            $ils = $connection->table('ils')->where('loc_airport_ident', $icao)->where("loc_runway_name", $runway)->whereNot("type", "T")->get();
            $frequency = $ils->pluck("frequency")->toArray();
            $new_ils = [];
            if (count($frequency) != null) {
                $reformattedNumber = substr_replace($frequency[0], ".", 3, 0) . " Mhz" ?? NULL;
                $ils[0]->frequency = $reformattedNumber;
                $new_ils = [];
                $new_ils["ident"] = $ils[0]->ident;
                $new_ils["type"] = $ils[0]->type;
                $new_ils["frequency"] = $ils[0]->frequency;
                $new_ils["loc_runway_name"] = $ils[0]->loc_runway_name;
                $new_ils["loc_heading"] = $ils[0]->loc_heading;
            }else{
                $new_ils["ident"] = NULL;
                $new_ils["type"] = NULL;
                $new_ils["frequency"] = NULL;
                $new_ils["loc_runway_name"] = NULL;
                $new_ils["loc_heading"] = NULL;
                $new_ils["runway"] = $runway;
                $new_ils["Approch"] = "RNAV";
            }



            $ils = collect($new_ils);
        }

        return $ils;
    }

    public function Get_info_all_airac($icao, $runway = NULL)
    {
        $approach = $this->get_approach($icao, $runway);
        $departure = $this->get_departure($icao, $runway);
        $ils = "";
        $ils_info = $this->get_ils_information($icao, $runway);
        $q = collect(["app" => $approach, "depa" => $departure, "ils" => $ils, "ils_info" => $ils_info]);
        return $q;
    }

    public function decode_rwy($value)
    {
        $sr = explode("/", $value);

        if (count($sr) > 2) {
            $arr = "/[0-9]{2}[L|R|C]?/";
            preg_match_all($arr, $sr[0], $matches);
            $sr["ARR"] = $matches[0][0];
            $q = [
                "ARR" => $sr["ARR"],
                "DEP" => $sr["ARR"]
            ];
        } else {
            $arr = "/[0-9]{2}[L|R|C]?/";
            preg_match_all($arr, $sr[0], $matches);
            $sr["ARR"] = $matches[0][0];
            preg_match_all($arr, $sr[1], $matches);
            $sr["DEP"] = $matches[0][0];
            $q = [
                "ARR" => $sr["ARR"],
                "DEP" => $sr["DEP"]
            ];
        }

        return $q;
    }
}
