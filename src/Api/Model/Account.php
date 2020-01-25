<?php

namespace Tustin\Byte\Api\Model;

use Tustin\Byte\Api\Model\Model;

use Carbon\Carbon;

class Account extends Model
{
    public string $id;

    public bool $isFollowing;

    public bool $isFollowed;

    public Carbon $registrationDate;

    public string $username;

    public string $backgroundColor;

    public string $foregroundColor;

    public int $followerCount;

    public int $followingCount;

    public int $loopCount;

    public int $loopsConsumedCount;


    private ?object $cache = null;

    public function __construct(\GuzzleHttp\Client $client, object $data)
    {
        parent::__construct($client);

        $this->id = $data->id;
        
        // Need to default these since they aren't included in the /me endpoint.
        $this->isFollowing = $data->isFollowing ?? false;
        $this->isFollowed = $data->isFollowed ?? false;

        $this->registrationDate = Carbon::createFromTimestamp($data->registrationDate);
        $this->username = $data->username;
        $this->backgroundColor = $data->backgroundColor;
        $this->foregroundColor = $data->foregroundColor;
        $this->followerCount = $data->followerCount;
        $this->followingCount = $data->followingCount;
        $this->loopCount = $data->loopCount;
        $this->loopsConsumedCount = $data->loopsConsumedCount;
    }
}