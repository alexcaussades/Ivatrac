<?php

namespace App\Http\Controllers;

use App\Models\loggin;
use Illuminate\Http\Request;

class logginController extends Controller
{
    
    public function log($type, $message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = $type;
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function warningLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "warning";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function errorLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "error";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function infoLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "info";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function debugLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "debug";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function successLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "success";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function criticalLog($message, $user, $ip, $users_admin_id)
    {
        $log = new loggin();
        $log->type = "critical";
        $log->message = $message;
        $log->user = $user;
        $log->ip = $ip;
        $log->users_admin_id = $users_admin_id;
        $log->save();
    }

    public function getLoggins()
    {
        return loggin::all();
    }
    

    public function getLoggin($id)
    {
        return loggin::where("id", $id)->first();
    }

    public function deleteLoggins()
    {
        loggin::truncate();
    }

        
    public function searchTypeLoggin($type)
    {
        return loggin::where("type", $type)->get();
    }   

    
    public function getLogginsByUser($user)
    {
        return loggin::where("user", $user)->get();
    }

    public function getLogginsByIp($ip)
    {
        return loggin::where("ip", $ip)->get();
    }

    public function getLogginsByUsersAdminId($users_admin_id)
    {
        return loggin::where("users_admin_id", $users_admin_id)->get();
    }

    public function getLogginsByUsersAdmin($users_admin)
    {
        return loggin::where("users_admin", $users_admin)->get();
    }
}
