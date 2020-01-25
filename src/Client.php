<?php
namespace Tustin\Byte;

use Tustin\Byte\Http\HttpClient;
use Tustin\Byte\Http\ResponseParser;
use Tustin\Byte\Http\TokenMiddleware;
use Tustin\Byte\Http\ResponseHandlerMiddleware;

use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;

class Client extends HttpClient
{
    private array $guzzleOptions;

    private string $authorizationToken;

    public function __construct(array $guzzleOptions = [])
    {
        if (!isset($guzzleOptions['handler']))
        {
            $guzzleOptions['handler'] = HandlerStack::create();
        }

        $this->guzzleOptions = $guzzleOptions;

        $this->httpClient = new \GuzzleHttp\Client($this->guzzleOptions);

        $config  = $this->httpClient->getConfig();
        $handler = $config['handler'];

        $handler->push(
            Middleware::mapResponse(
                new ResponseHandlerMiddleware
            )
        );
    }

    /**
     * Create a new Client instance.
     *
     * @param array $guzzleOptions Guzzle options
     * @return \Tustin\CallOfDuty\Client
     */
    public static function create(array $guzzleOptions = []) : Client
    {
        return new static($guzzleOptions);
    }
    
    /**
     * Login with your byte token.
     * 
     * @Temp: This will be temporary until I can reverse their Google client_id and cient_secret and implement Google sign-in.
     *
     * @param string $token Authorization token
     * @return Client
     */
    public function login(string $token) : Client
    {
        $this->authorizationToken = $token;

        $this->pushTokenMiddleware();

        return $this;
    }

    /**
     * Pushes TokenMiddleware onto the HandlerStack with the necessary header information.
     *
     * @return void
     */
    private function pushTokenMiddleware() : void
    {
        $config  = $this->httpClient->getConfig();
        $handler = $config['handler'];

        $handler->push(
            Middleware::mapRequest(
                new TokenMiddleware($this->authorizationToken())
            )
        );
    }

    /**
     * Gets the authorization token for the account.
     *
     * @return string
     */
    public function authorizationToken() : string
    {
        return $this->authorizationToken;
    }

    public function __call($method, array $parameters)
    {
        $class = "\\Tustin\\Byte\\Api\\" . ucwords($method);

        if (class_exists($class))
        {
            return new $class($this->httpClient);
        }

        throw new \BadMethodCallException("$method not found");
    }
}