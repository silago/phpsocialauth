<?php
namespace Phpsocialauth\Providers;
use Phpsocialauth\Interfaces\IAuthProvider;

class GooglePlay implements IAuthProvider
{
    
    protected $client = null;
    protected $client_id = null; //sub
        
    protected static function init($config = []) {
        return new static($config);
    }

    protected function __construct($config)
    {
        $google_id    = $config["id"];
        $secret       = $config["secret"];
        $client_id    = $config["client_id"];

        $this->client = new \Google_Client();
        $this->client->setClientId($google_id);
        $this->client->setClientSecret($secret);
    } 

    public function getUserId(array $auth_data = null): ? string
    {
        $payload = $client->verifyIdToken($auth_data['token']);
        if (! $payload || ! \is_array($payload)) {
            return null;
        }

        if (! isset($payload[$this->client_id])) {
            throw new Exception('invalid format response: undefined key ' . $this->client_id); 
        }
        return $payload[$this->client_id);
    }
}
