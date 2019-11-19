<?php

namespace Tylx\Laravelpackagetest\Facades;
use Illuminate\Support\Facades\Facade;
class Laravelpackagetest extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravelpackagetest';
    }
}