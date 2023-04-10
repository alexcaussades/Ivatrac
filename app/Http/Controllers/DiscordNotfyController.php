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
        $reponse = Http::post("https://discord.com/api/webhooks/1094916096324284477/Ulamwc2-t-asqRJyPabHwKuJ0Eel0_1hqphweOiIaLzoRQFr3vScn1Zv40xp7XAMu4Q3",[
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


    public function PostNotify()
    {
        $reponse = Http::post("https://discord.com/api/webhooks/1094916096324284477/Ulamwc2-t-asqRJyPabHwKuJ0Eel0_1hqphweOiIaLzoRQFr3vScn1Zv40xp7XAMu4Q3",[
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
}
