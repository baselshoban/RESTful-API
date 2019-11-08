<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Article extends Model
{
    protected $fillable = [
    	'id',
    	'title',
    	'content'
    ];

    public function categories()
    {
    	return $this->belongsToMany(Category::class)
    		->select('id', 'name');
    }
}
