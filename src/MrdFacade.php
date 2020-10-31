<?php

namespace Dg482\Mrd;

use Illuminate\Support\Facades\Facade;

class MrdFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mrd';
    }
}
