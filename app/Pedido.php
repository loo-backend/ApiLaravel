<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Pedido extends Eloquent
{
    protected $fillable =[
    	'usuario',
    	'produto_id',
    	'quantidade'
    ];
}