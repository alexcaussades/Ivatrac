<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordUsersMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmRegisterUsersMail;
use App\Mail\InformationRegisterUsers;
use App\Mail\verrifMailUsers;

class MailRegisterController extends Controller
{
    public function MailRegister($lastId)
    {
        $users = new usersController();
        $lastIdi = $users->get_info_user($lastId);
        Mail::to("alexandre.caussades@hotmail.com")->send(new InformationRegisterUsers($lastIdi));
    }

    public function ConfirmRegister($lastId, $password)
    {
        $users = new usersController();
        $user = $users->get_info_user($lastId);
        Mail::to($user->email)->send(new ConfirmRegisterUsersMail($user, $password));
    }

    public function reset_password($email, $password)
    {
        Mail::to($email)->send(new ResetPasswordUsersMail($password));
    }

    public function verrify_email($lastId)
    {
        $users = new usersController();
        $user = $users->get_info_user($lastId);
        Mail::to($user->email)->send(new verrifMailUsers($user));

    }
}
