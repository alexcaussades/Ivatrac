<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Mail;
use App\Mail\InformationRegisterUsers;

class MailRegisterController extends Controller
{
    public function MailRegister($lastId)
    {
        $users = new usersController();
        $user = $users->get_info_user($lastId);
        Mail::to("alexandre.caussades@hotmail.com")->send(new InformationRegisterUsers($user));
    }
}
