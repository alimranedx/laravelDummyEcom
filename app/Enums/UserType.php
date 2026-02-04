<?php

namespace App\Enums;

enum UserType: int
{
    case ADMIN = 1;
    case USER = 2;
    case SUPER_ADMIN = 127;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }
}
