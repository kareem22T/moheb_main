<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'thumbnail_path',
        'category_id'
    ];

    // relationships
    public function titles()
    {
        return $this->hasMany('App\Models\Article_Title', 'article_id');
    }

    public function contents()
    {
        return $this->hasMany('App\Models\Article_Content', 'article_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'article_tag', 'article_id', 'tag_id', 'id', 'id');
    }

}
