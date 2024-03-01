<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'symbol',
        'name'
    ];

    public $timestamps = false;

    //relationships
    public function category_names()
    {
        return $this->hasMany('App\Models\Category_Name', 'language_id');
    }

    public function term_names()
    {
        return $this->hasMany('App\Models\Term_Name', 'language_id');
    }

    public function term_titles()
    {
        return $this->hasMany('App\Models\Term_Title', 'language_id');
    }

    public function term_contents()
    {
        return $this->hasMany('App\Models\Term_Content', 'language_id');
    }

    public function term_sounds()
    {
        return $this->hasMany('App\Models\Term_Sound', 'language_id');
    }

    public function article_titles()
    {
        return $this->hasMany('App\Models\Article_Title', 'language_id');
    }

    public function article_contents()
    {
        return $this->hasMany('App\Models\Article_Content', 'language_id');
    }

}
