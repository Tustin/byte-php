<?php

namespace Tustin\Byte\Exception;

use Tustin\Byte\Http\JsonStream;

class ByteApiException extends \Exception
{
    public function __construct(JsonStream $stream)
    {
        $data = $stream->jsonSerialize();

        $message = 'Temp';
        $code = 69;

        if (isset($message, $code))
        {
            parent::__construct($message, $code);
        }
    }
}