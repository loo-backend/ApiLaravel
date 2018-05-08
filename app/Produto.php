<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Produto extends Eloquent
{
    protected $fillable =[
    	'titulo',
    	'fabricante',
    	'preco'
    ];
}
