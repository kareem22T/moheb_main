<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About_description extends Model
{
    use HasFactory;
    protected $fillable = [
        "language_id",
        "description"
    ];


    public $timestamps = false;
    public $table = "about_descriptions";
    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
