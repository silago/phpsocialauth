<?php
namespace Phpsocialauth\Providers;
use Phpsocialauth\Interfaces\IAuthProvider;

class Facebook implements IAuthProvider
{

    protected const URL="https://graph.facebook.com/";
    protected $version ="v3.0";
    public static function init( $config = [] ) {
        $item = new static($config);
        return $item;
    }

    protected function __construct($config = [] ) {
        if (array_key_exists("version", $config)) {
            $this->version = $config["version"];
        }
    }

    public function getUserId($auth_data = []) 
    {
        $token = $auth_data['token'];
        return $this->getFBId($token);
    }

    function getFBId($token)
    {
        $ch = curl_init();
        $url= self::URL . $this->version . "me?access_token=" . $token;
        curl_setopt($ch, CURLOPT_URL,$url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // The following ensures SSL always works. A little detail:
        // SSL does two things at once:
        //  1. it encrypts communication
        //  2. it ensures the target party is who it claims to be.
        // In short, if the following code is allowed, CURL won't check if the
        // certificate is known and valid, however, it still encrypts communication.

        curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        $result = curl_exec($ch);
        curl_close($ch);
        $fb_profile = json_decode( $result, true);
        if ($id = $fb_profile["id"]) {
            return $id;
        };
    }
}
