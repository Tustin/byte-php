<?php
namespace Tustin\Byte\Api;

use Tustin\Byte\Api\Model\Post;


class Posts extends Api
{
    public function fetch(string $postId) : Post
    {
        return new Post($this->httpClient, $postId);
    }

    public function create(string $videoContents, string $thumbnailContents, string $caption = "") : Post
    {
        $videoId = $this->uploadData('video/mp4', $videoContents);
        $imageId = $this->uploadData('image/jpeg', $thumbnailContents);

        $response = $this->postJson('post', [
            'videoUploadID' => $videoId,
            'caption' => $caption,
            'thumbUploadID' => $imageId
        ]);

        return new Post($this->httpClient, $response->id);
    }

    public function test(string $img)
    {
        $imgId = $this->uploadData('image/jpeg', $img);

        $this->putJson('account/me', [
            'username' => 'Tustin',
            'avatarUploadID' => $imgId
        ]);
    }
}