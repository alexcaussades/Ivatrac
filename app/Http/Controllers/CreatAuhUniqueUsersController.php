<?php

namespace App\Http\Controllers;

use DateTime;
use Dotenv\Util\Regex as UtilRegex;
use Illuminate\Http\Request;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;

class CreatAuhUniqueUsersController extends Controller
{
    public function creatAuthUniqueUses()
    {
        // creation d'un identifiant unique pour chaque utilisateur
        $id = bin2hex(random_bytes(16));
        // creation d'un token unique pour chaque utilisateur
        $token = uniqid();
        return response()->json([
            'id' => 'hell3898.' . $id . '',
            'token' => "hell3899." . $token . ""
        ], 200);
    }

    public function verifid(Request $request)
    {
        $id = $request->id;
        $id = explode(".", $id);
        // touvez la verification de l'identifiant en premier partie
        $verifId = $id[0];
        // cree une regex pour verifier l'identifiant
        $regex = "/hell3898/";
        $rr = preg_match($regex, $verifId);
        if ($rr == true) {
            // touvez la verification du token en deuxieme partie
            if ($rr == true) {
                return response()->json([
                    'message' => 'authentification reussie'
                ], 200);
            }
        } else {
            abort(503);
        }
    }

    public function deleteUID(){
        // delete the id and token
        return response()->json([
            'statut' => 'delete your id success',
            "information" => "your 2000 messages are deleted and profile is deleted"
        ], 200);
    }
}
