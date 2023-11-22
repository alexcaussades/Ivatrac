<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class metar_favModel extends Model
{
    protected $table = "metar_fav";
    protected $fillable = [
        "id_user",
        "vid",
        "icao"
    ];
}
