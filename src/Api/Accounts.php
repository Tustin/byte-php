<?php
namespace Tustin\Byte\Api;

use Tustin\Byte\Api\Model\Account;

class Accounts extends Api
{
    public function me() : object
    {
        return $this->get('account/me');
    }

    public function search(string $username) : \Generator
    {
        $results = $this->get('account/prefix/' . $username);

        foreach ($results->accounts as $account)
        {
            yield new Account($this->httpClient, $account);
        }
    }
}