<?php
namespace Tustin\Byte\Api;



class Accounts extends Api
{
    public function me() : object
    {
        return $this->get('account/me');
    }
}