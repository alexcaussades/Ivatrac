<?php

namespace App\Http\Controllers;

use PharIo\Manifest\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class AuthIVAOController extends Controller
{
    public function sso(Request $request, $url = "home")

    {
        // Now we can take care of the actual authentication
        $client_id = env("ivao_api_client_id");
        $client_secret = env("ivao_api_client_secret");
        $redirect_uri = route("callback");
        // Get all URLs we need from the server
        $openid_url = "https://api.ivao.aero/.well-known/openid-configuration";
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === false) {
            /* Handle error */
            die("Error while getting openid data");
        }
        $openid_data = json_decode($openid_result, true);

        $base_url = $openid_data["authorization_endpoint"];
        $reponse_type = "code";
        $scopes = "profile configuration email bookings:write friends friends:read friends:write flight_plans:read flight_plans:write tracker";
        $state = rand(100000, 999999); // Random string to prevent CSRF attacks

        $query = [
            "response_type" => $reponse_type,
            "client_id" => $client_id,
            "scope" => $scopes,
            "redirect_uri" => $redirect_uri,
            "state" => $state,
        ];
        $full_url = "$base_url?" . http_build_query($query);

        if (isset($request->code) && isset($request->state)) {
            // User has been redirected back from the login page

            $code = $request->code; // Valid only 5 minutes

            $token_req_data = [
                "grant_type" => "authorization_code",
                "code" => $code,
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_uri" => $redirect_uri,
            ];

            // use key 'http' even if you send the request to https://...
            $token_options = [
                "http" => [
                    "header" =>
                    "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "POST",
                    "content" => http_build_query($token_req_data),
                ],
            ];
            $token_context = stream_context_create($token_options);
            $token_result = file_get_contents(
                $openid_data["token_endpoint"],
                false,
                $token_context
            );
            if ($token_result === false) {
                /* Handle error */
                die("Error while getting token");
            }

            $token_res_data = json_decode($token_result, true);

            $access_token = $token_res_data["access_token"]; // Here is the access token
            $refresh_token = $token_res_data["refresh_token"]; // Here is the refresh token

            session([
                "ivao_tokens" => json_encode([
                    "access_token" => $access_token,
                    "refresh_token" => $refresh_token,
                ]),
            ]);
            return redirect()->route($url);

            // header("Location: user.php"); // Remove the code and state from URL since they aren't valid anymore
        } elseif (session()->has("ivao_tokens")) {
            // User has already logged in

            $tokens = json_decode(session("ivao_tokens"), true);
            $access_token = $tokens["access_token"];
            $refresh_token = $tokens["refresh_token"];

            // Now we can use the access token to get the data

            $user_options = [
                "http" => [
                    "header" => "Authorization: Bearer $access_token\r\n",
                    "method" => "GET",
                    "ignore_errors" => true,
                ],
            ];
            $user_context = stream_context_create($user_options);
            $user_result = file_get_contents(
                $openid_data["userinfo_endpoint"],
                false,
                $user_context
            );
            $user_res_data = json_decode($user_result, true);
            dd($user_res_data);
            if ($user_res_data != null) {
                $request->merge([
                    "id" => $user_res_data["id"],
                ]);
                $users = new usersController();
                $users->connect_via_ivao($request, $user_res_data);
            }
            if (
                isset($user_res_data["description"]) &&
                ($user_res_data["description"] ===
                    "This auth token has been revoked or expired" or
                    $user_res_data["description"] ===
                    "Couldn't decode auth token")
            ) {
                // Access token expired, using refresh token to get a new one

                $token_req_data = [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                ];

                $token_options = [
                    "http" => [
                        "header" =>
                        "Content-type: application/x-www-form-urlencoded\r\n",
                        "method" => "POST",
                        "content" => http_build_query($token_req_data),
                        "ignore_errors" => true,
                    ],
                ];
                $token_context = stream_context_create($token_options);
                $token_result = file_get_contents(
                    $openid_data["token_endpoint"],
                    false,
                    $token_context
                );
                if ($token_result === false) {
                    /* Handle error */
                    die("Error while refreshing token");
                }

                $token_res_data = json_decode($token_result, true);

                $access_token = $token_res_data["access_token"]; // Here is the new access token
                $refresh_token = $token_res_data["refresh_token"]; // Here is the new refresh token

                session([
                    "ivao_tokens" => json_encode([
                        "access_token" => $access_token,
                        "refresh_token" => $refresh_token,
                    ]),
                ]);
                return redirect()->route($url);
            } else {
                // dd($user_res_data); // Display user data fetched with the access token
                return redirect()->route($url);
                //return $this->handlerLogin($user_res_data);
            }
        } else {
            // First visit : Unauthenticated user
            return redirect($full_url);
        }
    }

    public function handlerLogin($user)
    {
        dd($user);
        function staffLogin($data)
        {
            $staff = [];
            foreach ($data as $key => $value) {
                $staff[] = $value["id"];
            }

            $staff = implode(",", $staff);
            return $staff;
        }

        $finduser = User::where("vid", intval($user["id"]))->first();

        if ($finduser) {
            $finduser->firstname = $user["firstName"];
            $finduser->lastname = $user["lastName"];
            $finduser->email = $user["email"];
            $finduser->ratingatc = intval($user["rating"]["atcRating"]["id"]);
            $finduser->ratingpilot = intval(
                $user["rating"]["pilotRating"]["id"]
            );
            $finduser->division = $user["divisionId"];
            $finduser->country = $user["countryId"];
            $finduser->staff = staffLogin($user["userStaffPositions"]);
            $finduser->save();
            Auth::login($finduser);
        } else {
            $newUser = User::create([
                "vid" => intval($user["id"]),
                "firstName" => $user["firstName"],
                "lastName" => $user["lastName"],
                "email" => $user["email"],
                "ratingatc" => intval($user["rating"]["atcRating"]["id"]),
                "ratingpilot" => intval($user["rating"]["pilotRating"]["id"]),
                "division" => $user["divisionId"],
                "country" => $user["countryId"],
                "staff" => staffLogin($user["userStaffPositions"]),
                "password" => bcrypt("ivao"), // We are setting a default password for the user because Laravel requires it, but it won't be used since the only way to login is through IVAO SSO.
            ]);

            Auth::login($newUser);
        }

        $userlog = Auth::user();

        return redirect()->route("home");
    }
    public function logout()
    {
        Auth::logout();
        session()->forget("ivao_tokens");
        return redirect()->route("welcome");
    }

    public function revoke_token()
    {
    }


    public function callback()
    {
        return view("callback");
    }
}
