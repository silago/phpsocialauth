<?php
namespace Phpsocialauth\Interfaces;

interface IAuthProvider
{
    public function getUserId(array $auth_data = null): ? string;
    public static function init(array $auth_data = []): IAuthProvider;
}
