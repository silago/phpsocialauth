<?php
/**
 * @author Alexandr Budko <alexandr.budko@gmail.com>
 * @date   14.05.2018 16:18
 */

namespace Stark\Perf\Service\Auth\Providers;

use Stark\Perf\Service\Auth\Interfaces\IAuthProvider;

class Device implements IAuthProvider
{
    public static function getUserId(array $auth_data = null): ? string
    {
        return '0';
    }
}
