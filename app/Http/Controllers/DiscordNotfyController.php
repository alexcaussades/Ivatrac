<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordNotfyController extends Controller
{


    public function index()
    {
        return $this->PostNotify();
    }

    public function PostNotify2()
    {
        $reponse = Http::post("https://discord.com/api/webhooks/1095038427973558433/DFUjcyw9kMDlcB2N-XCsKQrr2hJpGzQN_YDik1H4oqplDNPOl1usJlBL7I9z_O3Kko-9", [
            "embeds" => [
                [
                    "title" => "Ceci est un test pour le webhook et les notifications discord pour les feactures de l'application",
                    "description" => "Bonjour tout le monde, je suis un test pour le webhook et les notifications discord pour les feactures de l'application, pour plus d'information veuillez contacter le developpeur de l'application ou le support technique de l'application.",
                    "color" => 16711680
                ]
            ]
        ]);
        return $reponse->json();
    }


    public function PostNotify()
    {
        $reponse = Http::post("https://discord.com/api/webhooks/1094916096324284477/Ulamwc2-t-asqRJyPabHwKuJ0Eel0_1hqphweOiIaLzoRQFr3vScn1Zv40xp7XAMu4Q3", [
            "embeds" => [
                [
                    "title" => "test",
                    "description" => "test",
                    "color" => 16711680
                ]
            ]
        ]);
        return $reponse->json();
    }

    public function newAddWhiteList($nameRp, $discordName, $email)
    {
        $reponse = Http::post("https://discord.com/api/webhooks/1095038427973558433/DFUjcyw9kMDlcB2N-XCsKQrr2hJpGzQN_YDik1H4oqplDNPOl1usJlBL7I9z_O3Kko-9", [
            "embeds" => [
                [
                    "title" => "Ajout de la liste d'attente de la whitelist de " . $nameRp,
                    "description" => "l'utilisateur " . $nameRp . " a fait une demande d'ajout sur la liste d'attente de la whitelist du serveur. sont UsersName Discord est : " . $discordName . "\n Adresse email : " . $email . "\n URL de la demande : https://127.0.0.1:8000/whitelist/LudivicRamirez/34/",
                    "color" => 16711680
                ]
            ]
        ]);
        return $reponse->json();
    }
}
