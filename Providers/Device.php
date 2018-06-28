<?php
namespace Phpsocialauth\Providers;
use Phpsocialauth\Interfaces\IAuthProvider;

class Device implements IAuthProvider
{
    protected static function init($config = []) {
        return new static($config);
    }

    protected function __construct($config = [])
    {
    } 

    public function getUserId($auth_data = []) 
    {
        return '0';
    }
}
