<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DynamicCart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dynamic-cart';
    }
}
