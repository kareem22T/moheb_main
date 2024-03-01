<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term_Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'term_id',
        'language_id'
    ];

    public $timestamps = false;

    protected $table = 'term_titles';

    // relationships
    public function term()
    {
        return $this->belongsTo('App\Models\Term', 'term_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
