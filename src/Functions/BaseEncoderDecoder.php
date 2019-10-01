<?php

namespace MathPHP\Functions;

use MathPHP\Exception;
use MathPHP\Number\ArbitraryInteger;

/**
 * Utility functions to manipulate numerical strings with non-standard bases and alphabets
 */
class BaseEncoderDecoder
{
    /** string alphabet of base 64 numbers */
    const RFC3548_BASE64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    /** string alphabet of file safe base 64 numbers */
    const RFC3548_BASE64_FILE_SAFE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    /** string alphabet of base 32 numbers */
    const RFC3548_BASE32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Get the default alphabet for a given number base
     *
     * @param int $base
     * @return string
     */
    protected static function getDefaultAlphabet(int $base): string
    {
        switch ($base) {
            case 2:
            case 8:
            case 10:
                $offset = '0';
                break;
            case 16:
                $offset = '0123456789abcdef';
                break;
            default:
                $offset = chr(0);
                break;
        }
        return $offset;
    }

    /**
     * Convert to an arbitrary base and alphabet
     *
     * @param ArbitraryInteger $number
     * @param int $base
     * @param string $alphabet
     *
     * @return string
     */
    public static function toBase(ArbitraryInteger $number, int $base, $alphabet = null): string
    {
        if ($base > 256) {
            throw new Exception\BadParameterException("Number base cannot be greater than 256.");
        }
        if ($alphabet === null) {
            $alphabet = self::getDefaultAlphabet($base);
        }
        $base_256 = $number->getBinary();
        $result = '';
        while ($base_256 !== '') {
            $carry = 0;
            $next_int = $base_256;
            $len = strlen($base_256);
            $base_256 = '';
            for ($i = 0; $i < $len; $i++) {
                $chr = ord($next_int[$i]);
                $int = intdiv($chr + 256 * $carry, $base);
                $carry = ($chr + 256 * $carry) % $base;
                // or just trim off all leading chr(0)s
                if ($base_256 !== '' || $int > 0) {
                    $base_256 .= chr($int);
                }
            }
            if (strlen($alphabet) == 1) {
                $result = chr(ord($alphabet) + $carry) . $result;
            } else {
                $result = $alphabet[$carry] . $result;
            }
        }
        return $result;
    }

    public function createArbitraryInteger(): ArbitraryInteger
    {
    }
}
