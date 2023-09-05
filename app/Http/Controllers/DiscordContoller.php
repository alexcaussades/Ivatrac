<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordContoller extends Controller
{
    public function url_webhooks()
    {
        $url = "https://discord.com/api/webhooks/1148640987455946782/I4N-04Fm0G9mmje6iELgeOfjyEC9Im2kHfbVTLQaIarkHzWReYNmh1NMpmD0SRwwfk_b";
        return $url;
    }

    public function url_test_webhooks()
    {
        $url = "https://discord.com/api/webhooks/1148642625272959007/kI8Ar2GT3VsAABwyMm8yYtpwVYUwOhLtJvoIcMd1a9cM1klBASNvf91PWvsaTGQKuF_C";
        return $url;
    }

    public function send_webhooks(Request $request)
    {
        $push = Http::post($this->url_test_webhooks(), [
            "content" => $request->message
        ]);
        return $push;
    }

    public function push_github(Request $request)
    {
        $github = new GithubController();
        $github = $github->send_issue($request);
        return $github;
    }

    public function description(Request $request)
    {
        $text = $request->body;
        if($request->link != null){
          return "Type:".$request->labels." \n\n". $text .= "\n\n Link direct github: [Issue](" . $request->link . ")";
        }else{
          return "Type:".$request->labels." \n\n". $text;
        }
    }

    public function send_feedback(Request $request)
    {
        $request->validate([
            "body" => "required|min:25|max:255",
            "labels" => "required",
        ]);
        $request->merge([
            "body" => $request->body,
            "user_id" => $request->user_id,
            "labels" => $request->labels,
            "link"=> $request->link,
        ]);
        $usersController = new UsersController();
        $user = $usersController->get_info_user($request->user_id);
        $push = Http::post($this->url_webhooks(), [
            "avatar_url" => "https://i.pinimg.com/originals/99/1e/53/991e534b8f6038f4bdf67a97a7984822.jpg",
            "embeds" => [
                [
                    "title" => "Feedback from: " . $user->name . " (VID: " . $user->vid . ")",
                    "description" => $this->description($request),
                    "url" => $request->link ?? null,
                    "color" => "16711680",
                    "footer" => [
                        "text" => "Feedback Form the website",
                    ],
                    "timestamp" => date("Y-m-d H:i:s")
                ]
            ]

        ]);
        return $push;
    }
}
