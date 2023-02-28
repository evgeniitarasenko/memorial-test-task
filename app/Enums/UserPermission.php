<?php

namespace App\Enums;


use Spatie\Enum\Enum;

/**
 * @method static self administrator()
 * @method static self guest()
 */
final class UserPermission extends Enum
{
    protected static function labels(): array
    {
        return [
            'administrator' => 'Administrator',
            'guest' => 'Guest',
        ];
    }
}
