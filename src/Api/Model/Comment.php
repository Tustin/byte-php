<?php
namespace Tustin\Byte\Api\Model;

use Carbon\Carbon;

use Tustin\Byte\Api\Model\Post;
use Tustin\Byte\Api\Model\Model;
use Tustin\Byte\Api\Model\Account;

class Comment extends Model
{
    public string $id;

    public string $body;

    private string $authorId;

    public Carbon $date;

    private string $postId;

    public function __construct(\GuzzleHttp\Client $client, object $comment)
    {
        parent::__construct($client);

        // Since there's no API endpoint for getting a comment by it's ID (that I could find), I'm just passing the full object here.
        $this->id = $comment->id;
        $this->authorId = $comment->authorId;
        $this->date = Carbon::createFromTimestamp($comment->date);
        $this->postId = $comment->postID;

        // @TODO: Mentions
    }

    /**
     * Gets the comment author.
     *
     * @return Account
     */
    public function author() : Account
    {
        return new Account($this->httpClient, $this->authorId);
    }

    /**
     * Gets the post this comment is on.
     *
     * @return Post
     */
    public function post() : Post
    {
        return new Post($this->httpClient, $this->postId);
    }
}