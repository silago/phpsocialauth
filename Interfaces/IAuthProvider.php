<?php
namespace Phpsocialauth\Interfaces;

interface IAuthProvider
{
    public function getUserId($auth_data = [] );
    public static function init($auth_data = []);
}
