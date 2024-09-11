<?php

namespace Moontechs\OpenAIManagement\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Moontechs\OpenAIManagement\OpenAIManagement
 */
class OpenAIManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Moontechs\OpenAIManagement\OpenAIManagement::class;
    }
}
