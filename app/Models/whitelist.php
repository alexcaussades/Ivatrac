<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class whitelist extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_rp',
        'id_users',
        'naissance',
        'Profession',
        'savoir',
        'description',
    ];
}
