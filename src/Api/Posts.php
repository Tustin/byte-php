<?php
namespace Tustin\Byte\Api;

use Tustin\Byte\Api\Model\Post;


class Posts extends Api
{

    public function fetch(string $postId) : Post
    {
        return new Post($this->httpClient, $this->get('posts/id' . $postId));
    }

    public function create(string $videoContents, string $thumbnailContents, string $caption = "") : void
    {
        $videoId = $this->uploadData('video/mp4', $videoContents);
        $imageId = $this->uploadData('image/jpeg', $thumbnailContents);

        $data = $this->postJson('post', [
            'videoUploadID' => $videoId,
            'caption' => $caption,
            'thumbUploadID' => $imageId
        ]);
    }

    private function uploadData(string $contentType, string $contents) : string
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