<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "email",
        "comment",
        "term_id",
        "created_at",
        "updated_at"
    ];

    public function term()
    {
        return $this->belongsTo('App\Models\Term', 'term_id');
    }

}
