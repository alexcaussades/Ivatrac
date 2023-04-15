<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        "information"
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\users::class);
    }

    public function create_Roles($name, $information)
    {
        $this->name = $name;
        $this->information = $information;
        $this->save();
    }
    
}
