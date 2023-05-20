<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class AutModoController extends Controller
{
    protected $guard = "modo";
}
