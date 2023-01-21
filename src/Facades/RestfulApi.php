<?php

namespace Slait\RestfulApi\Facades;

use Illuminate\Support\Facades\Facade;

class RestfulApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'restfulapi';
    }
}
