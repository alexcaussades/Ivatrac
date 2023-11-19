<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DiscordContoller;


class GithubController extends Controller
{
    public function url_issue()
    {
        $url = "https://api.github.com/repos/alexcaussades/Ivatrac/issues";
        return $url;
    }

    public function token()
    {
        $token = env('Github_token_issue');
        return $token;
    }

    public function call_API()
    {
        $f = http::withToken($this->token())->post($this->url_issue());
        return $f;
    }

    public function send_issue(Request $request)
    {
        /**  Send issue to github on the repo alexcaussades/l10 is token is valid*/

        $users = new UsersController();

        $user = $users->get_info_user($request->user_id);
        if (!$user) {
            $user = [];
            $user = [
                "name" => "Anonymous",
                "vid" => "Anonymous"
            ];
            $issue = Http::withToken($this->token())->post($this->url_issue(), [
                "title" => "Feedback from: " . $user["name"],
                "body" => $request->body,
                "labels" => [
                    $request->labels
                ]
            ]);
            /** header location de l'issue */
            $url = $this->return_url($issue);
            return $url["html_url"];
        } else {
            $issue = Http::withToken($this->token())->post($this->url_issue(), [
                "title" => "Feedback from: " . $user->name . " (VID: " . $user->vid . ")",
                "body" => $request->body,
                "labels" => [
                    $request->labels
                ]
            ]);
            /** header location de l'issue */
            $url = $this->return_url($issue);
            return $url["html_url"];
        }
    }

    public function return_url($url)
    {
        $issue = $url->json();
        $issue_post = [];
        $issue_post = [
            "url" => $issue["url"],
            "html_url" => $issue["html_url"],
            "repository_url" => $issue["repository_url"],
            "labels" => $issue["labels"],
            "title" => $issue["title"],
        ];
        return $issue_post;
    }
}
