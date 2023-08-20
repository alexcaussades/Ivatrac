<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class friendlyUserModel extends Model
{
    use HasFactory;
    protected $table = "friend_online";
    protected $fillable = [
        "vid_friend",
        "name_friend",
        "id_user"
    ];
}
