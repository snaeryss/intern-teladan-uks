<?php

namespace App\Services;

/**
 * generator random string for unique id
 */
class RandomStringService
{
    /**
     * generate random string but numberic only
     * @return string random numberic with default length 5 digit
     */
    public static function numericOnly(int $length = 5): string
    {
        return substr(str_shuffle("0123456789"), 0, $length);
    }
}
