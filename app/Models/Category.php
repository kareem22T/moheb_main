<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'main_name',
        'description',
        'cat_type',
        'main_cat_id',
        'thumbnail_path'
    ];

    public $timestamps = false;

    protected $table = 'categories';

    // relationships
    public function names()
    {
        return $this->hasMany('App\Models\Category_Name', 'category_id');
    }

    public function sub_categories()
    {
        return $this->hasMany('App\Models\Category', 'main_cat_id');
    }

    public function terms()
    {
        return $this->hasMany('App\Models\Term', 'category_id');
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article', 'category_id');
    }

    public function main_category()
    {
        return $this->belongsTo('App\Models\Category', 'main_cat_id');
    }

}
