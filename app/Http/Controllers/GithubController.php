<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    public function url_issue(){
        $url = "https://api.github.com/repos/alexcaussades/L10/issues";
        return $url;
    }

    public function token(){
        $token = env('Github_token_issue');
        return $token;
    }

    public function call_API(){
        $f = http::withToken($this->token())->post($this->url_issue());
        return $f;

    }

    public function send_issue(Request $request){
        /**  Send issue to github on the repo alexcaussades/l10 is token is valid*/

        $users = new UsersController();
        
        $user = $users->get_info_user($request->user_id);
        if(!$user){
            $users = [
                "name" => "Anonymous",
                "vid" => "Anonymous"
            ];
        }
        $issue = Http::withToken($this->token())->post($this->url_issue(), [
            "title" => "Feedback from: " . $user->name . " (VID: ".$user->vid.")",
            "body" => $request->body,
            "labels" => [
                $request->labels
            ]
        ]);
        /** header location de l'issue */
        $url = $this->return_url($issue->header('location'));
        return $url;
    }

    public function return_url($url){
        $url = explode("/", $url);
        $new_url = "https://github.com/alexcaussades/L10/issues/" . $url[7];
        return $new_url;

    }
}

