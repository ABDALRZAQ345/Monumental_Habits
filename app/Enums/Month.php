<?php

namespace App\Enums;

enum Month
{
    case January;
    case February;
    case March;
    case April;
    case May;
    case June;
    case July;
    case August;
    case September;
    case October;
    case November;
    case December;

    public function days(?int $year = null): int
    {
        $year = $year ?? date('Y');

        return match ($this) {
            self::January, self::March, self::May, self::July,
            self::August, self::October, self::December => 31,
            self::April, self::June, self::September, self::November => 30,
            self::February => $this->isLeapYear($year) ? 29 : 28,
        };

    }

    private function isLeapYear(int $year): bool
    {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }
}
