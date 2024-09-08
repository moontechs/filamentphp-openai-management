<?php

namespace Moontechs\OpenaiBatchesManagement\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Moontechs\OpenaiBatchesManagement\OpenaiBatchesManagement
 */
class OpenaiBatchesManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Moontechs\OpenaiBatchesManagement\OpenaiBatchesManagement::class;
    }
}
