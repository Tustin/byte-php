<?php

namespace Tustin\Byte\Api;

use Tustin\Byte\Http\HttpClient;

abstract class Api extends HttpClient
{
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->httpClient = $client;
    }
}