<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories_description extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'category_id',
        'language_id'
    ];

    public $timestamps = false;

    // relationships
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }


}
