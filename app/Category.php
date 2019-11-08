<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    	'id',
    	'name'
    ];

    public function articles()
    {
    	return $this->belongsToMany(Article::class);
    }

    public function hasAnyArticles()
    {
    	return $this->articles()->count() > 0;
    }
}
