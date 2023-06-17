<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Env;
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
        $secret = env("METAR_API_KEY");
        $formatage = "Barer: x_LveP6GNrgdVJ9BxlmIUAJMOlfCMzTxNcnJC8zLgW0";

        $bb = Http::withHeaders([
            'Authorization' => $formatage,
            'Accept' => 'application/json',
        ])->get('https://avwx.rest/api/metar/lfpg/', [
            'token' => $secret,
        ]);
        
        return $bb;
    }
}
