<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'article_id',
        'language_id'
    ];

    public $timestamps = false;

    protected $table = 'article_titles';

    // relationships
    public function term()
    {
        return $this->belongsTo('App\Models\Article', 'article_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
