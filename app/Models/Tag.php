<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    public $timestamps = false;

    // relationships 
    public function terms()
    {
    return $this->belongsToMany('App\Models\Term', 'term_tag', 'term_id', 'tag_id', 'id', 'id');
    }

    public function articles()
    {
    return $this->belongsToMany('App\Models\Article', 'article_tag', 'article_id', 'tag_id', 'id', 'id');
    }

}
