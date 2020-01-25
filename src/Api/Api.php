<?php

namespace Tustin\Byte\Api;

use Tustin\Byte\Http\HttpClient;

abstract class Api extends HttpClient
{
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Uploads a file for byte to consume.
     *
     * @param string $contentType MIME type of the file.
     * @param string $contents The file contents.
     * @return string The upload ID for byte to consume.
     */
    protected function uploadData(string $contentType, string $contents) : string
    {
        $data = $this->postJson('upload', [
            'contentType' => $contentType
        ]);

        $uploadId = $data->uploadID;
        $uploadUrl = $data->uploadURL;

        $this->putFile($uploadUrl, $contents, [
            'Content-Type' => $contentType
        ]);

        return $uploadId;
    }
}