<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'hide',
        'thumbnail_path',
        'category_id'
    ];

    // relationships
    public function names()
    {
        return $this->hasMany('App\Models\Term_Name', 'term_id');
    }

    public function titles()
    {
        return $this->hasMany('App\Models\Term_Title', 'term_id');
    }

    public function contents()
    {
        return $this->hasMany('App\Models\Term_Content', 'term_id');
    }

    public function sounds()
    {
        return $this->hasMany('App\Models\Term_Sound', 'term_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function tags()
    {
    return $this->belongsToMany('App\Models\Tag', 'term_tag', 'term_id', 'tag_id', 'id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedByUser($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

}
