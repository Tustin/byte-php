<?php

namespace Tustin\Byte\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\Request;

use GuzzleHttp\Psr7\Response;

abstract class HttpClient
{
    protected $httpClient;

    private const API_BASE = 'https://api.byte.co/';

    private Response $lastResponse;

    public function __call($method, array $args)
    {
        if (!method_exists($this, $method))
        {
            throw new \BadMethodCallException("$method not found");
        }

        $this->resolve($args[0]);

        return call_user_func_array([
            $this,
            $method
        ], $args);
    }

    private function get(string $path = '', array $body = [], array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->get($path, [
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function post(string $path, array $body, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->post($path, [
            'form_params' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function postJson(string $path, array $body, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->post($path, [
            'json' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function postMultiPart(string $path, array $body, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->post($path, [
            'multipart' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function delete(string $path, array $headers = []) : ?object
    {
        return ($this->lastResponse = $this->httpClient->delete($path, [
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function deleteJson(string $path, array $body, array $headers = []) : ?object
    {
        return ($this->lastResponse = $this->httpClient->delete($path, [
            'json' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function patch(string $path, $body = null, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->patch($path, [
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function put(string $path, $body = null, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->put($path, [
            'form_params' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function putJson(string $path, $body = null, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->put($path, [
            'json' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function putFile(string $path, string $body = null, array $headers = []) : ?object
    {
        return ($this->lastResponse = $this->httpClient->put($path, [
            'body' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    private function putMultiPart(string $path, $body = null, array $headers = []) : object
    {
        return ($this->lastResponse = $this->httpClient->put($path, [
            'multipart' => $body,
            'headers' => $headers
        ]))->getBody()->jsonSerialize();
    }

    public function lastResponse() : Response
    {
        return $this->lastResponse;
    }

    /**
     * Resolves a relative path to a full URL for any given class.
     *
     * @param string $path The relative path
     * @return void
     */
    private function resolve(string &$path) : void
    {
        // Just return the path since it's a complete URL.
        if (substr($path, 0, 4) === 'http')
        {
            return;
        }

        $path = self::API_BASE . $path;
    }
}