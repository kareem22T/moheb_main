<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles_image extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'path'
    ];

    protected $table = 'articles_images';
}
