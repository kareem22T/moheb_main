<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "phone",
        "email",
        "facebook",
        "instagram",
        "privacy",
        "copy",
        "youtube",
        "x",
    ];

    protected $table = 'contact';
}
