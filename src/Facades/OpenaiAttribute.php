<?php

namespace Dmsemenov\OpenaiAttribute\Facades;

use Illuminate\Support\Facades\Facade;

class OpenaiAttribute extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'openai-attribute';
    }
}
