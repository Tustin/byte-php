<?php

namespace Tustin\Byte\Api\Model;

use Tustin\Byte\Api\Api;

abstract class Model extends Api
{
    private ?object $cache = null;

    public abstract function raw();
}