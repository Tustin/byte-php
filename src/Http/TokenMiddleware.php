<?php

namespace Tustin\Byte\Http;

use GuzzleHttp\Psr7\Request;

final class TokenMiddleware
{
    private $authorizationToken;

    public function __construct(string $authorizationToken)
    {
        $this->authorizationToken = $authorizationToken;
    }

    public function __invoke(Request $request, array $options = [])
    {
        return $request
        ->withHeader(
            'Authorization', $this->authorizationToken
        );
    }
}