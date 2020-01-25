<?php
namespace Tustin\Byte\Api\Model;

use Tustin\Byte\Api\Model\Account;
use Tustin\Byte\Api\Model\Comment;


class Post extends Model
{
    public string $postId;

    public function __construct(\GuzzleHttp\Client $client, string $postId)
    {
        parent::__construct($client);

        $this->postId = $postId;
    }

    /**
     * Likes the post.
     *
     * @return void
     */
    public function like() : void
    {
        $this->putJson('post/id' . $postId . '/feedback/like', [
            'postID' => $postId
        ]);
    }

    /**
     * Unlikes the post, if liked.
     *
     * @return void
     */
    public function unlike() : void
    {
        $this->deleteJson('post/id' . $postId . '/feedback/like', [
            'postID' => $postId
        ]);
    }

    /**
     * Reply with a comment on this post.
     *
     * @param string $message The comment contents.
     * @return Comment
     */
    public function comment(string $message) : Comment
    {
        $comment = $this->postJson('post/id' . $postId . '/feedback/comment', [
            'postID' => $postId,
            'body' => $message
        ]);

        return new Comment($this->httpClient, $comment);
    }

    /**
     * Removes the post.
     * 
     * Only works if you created this post.
     *
     * @return void
     */
    public function remove() : void
    {
        $this->delete('post/id' . $postId);
    }

    /**
     * Gets the comments on the post.
     *
     * @param string $cursor The cursor for the next iteration of comments (used for pagination).
     * @return \Generator
     */
    public function comments(string $cursor = '') : \Generator
    {
        // @TODO: Implement iterator for the cursor.
        $comments = $this->get('post/id' . $postId . '/feedback/comment');

        foreach ($comments->comments as $comment)
        {
            yield new Comment($this->httpClient, $comment);
        }
    }

    /**
     * Gets all the accounts that liked this post.
     *
     * @param string $cursor The cursor for the next iteration of comments (used for pagination).
     * @return \Generator
     */
    public function likes(string $cursor = '') : \Generator
    {
        // @TODO: Implement iterator for the cursor.
        $likes = $this->get('post/id' . $postId . '/feedback/like');

        foreach ($likes->accounts as $like)
        {
            yield new Account($this->httpClient, $like->id);
        }
    }

    /**
     * Gets the raw post object data.
     * 
     * Will return from cache first, if previously cached.
     *
     * @return object Post data.
     */
    private function raw() : object
    {
        return $this->cache ??= $this->get('post/id' . $postId);
    }
}