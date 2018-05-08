<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Book extends Eloquent
{
    protected $fillable =[
    	'title'
    ];

    public function author()
    {
        return $this->embedsOne(Author::class);
    }
}
