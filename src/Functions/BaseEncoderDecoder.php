<?php

namespace MathPHP\Functions;

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

    public function toBase(string $number, int $fromBase, int $toBase, string $fromAlphabet, string $toAlphabet): string
    {
    }

    public function createArbitraryInteger(): ArbitraryInteger
    {
    }
}
