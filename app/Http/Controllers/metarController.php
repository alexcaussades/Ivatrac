<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class metarController extends Controller
{
    //secret key github

    public function index()
    {
        return view('metar');
    }

    public function sercretMetarGitHub()
    {
        /** secret github chifrée sur la repo L10 */
        $secret = env("Token_secret_github");

        $bb = Http::post("https://api.github.com/repos/alexcaussades/L10", [
            "Token_secret_github" => $secret
        ]);
        dd($bb);
    }
}
