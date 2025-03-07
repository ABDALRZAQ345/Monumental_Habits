<?php

namespace App\Enums;

enum Days: int
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;

    public static function getDaysArray(): array
    {
        return [
            self::Sunday->name => self::Sunday->value,
            self::Monday->name => self::Monday->value,
            self::Tuesday->name => self::Tuesday->value,
            self::Wednesday->name => self::Wednesday->value,
            self::Thursday->name => self::Thursday->value,
            self::Friday->name => self::Friday->value,
            self::Saturday->name => self::Saturday->value,
        ];
    }
}
