# byte-php

A comprehensive PHP library for interacting with the byte API.

## Installation

Pull in the project with Composer:

`composer require tustin/byte-php`

## Usage

To get started, here's a simple script:

```php
require_once 'vendor/autoload.php';

use \Tustin\Byte\Client;

// You can pass any Guzzle options into Client.
$client = new Client();

$client->login('<byte authorization token>');

// Output all the data for your account.
var_dump($client->accounts()->me());
```
