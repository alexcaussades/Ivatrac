<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegisterUsersMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\InformationRegisterUsers;

class MailRegisterController extends Controller
{
    public function MailRegister($lastId)
    {
        $users = new usersController();
        $lastId = $users->get_info_user($lastId);
        Mail::to("alexandre.caussades@hotmail.com")->send(new InformationRegisterUsers($lastId));
    }

    public function ConfirmRegister($lastId, $password)
    {
        $users = new usersController();
        $user = $users->get_info_user($lastId);
        Mail::to($user->email)->send(new ConfirmRegisterUsersMail($user, $password));
    }
}
