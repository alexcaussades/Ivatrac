<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pirep extends Model
{
    use HasFactory;
    protected $table = "pirep";
    protected $fillable = [
        "users_id",
        "departure",
        "arrival",
        "aircraft",
        "fpl"
    ];
}
