<?php
/*
 * This is a solution posted by Dereleased to Stack Overflow
 *
 * http://stackoverflow.com/a/2237745
 */

define('CRYPT_ALPHA','./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
/**
 * Decodes a base64 string based on the alphabet set in constant CRYPT_ALPHA
 * Uses string functions rather than binary transformations, because said
 * transformations aren't really much faster in PHP
 * @params string $str  The string to decode
 * @return string       The raw output, which may include unprintable characters
 */
function base64_decode_ex($str) {
    // set up the array to feed numerical data using characters as keys
    $alpha = array_flip(str_split(CRYPT_ALPHA));
    // split the input into single-character (6 bit) chunks
    $bitArray = str_split($str);
    $decodedStr = '';
    foreach ($bitArray as &$bits) {
        if ($bits == '$') { // $ indicates the end of the string, to stop processing here
            break;
        }
        if (!isset($alpha[$bits])) { // if we encounter a character not in the alphabet
            return false;            // then break execution, the string is invalid
        }
        // decbin will only return significant digits, so use sprintf to pad to 6 bits
        $decodedStr .= sprintf('%06s', decbin($alpha[$bits]));
    }
    // there can be up to 6 unused bits at the end of a string, so discard them
    $decodedStr = substr($decodedStr, 0, strlen($decodedStr) - (strlen($decodedStr) % 8));
    $byteArray = str_split($decodedStr, 8);
    foreach ($byteArray as &$byte) {
        $byte = chr(bindec($byte));
    }
    return join($byteArray);
}

/**
 * Takes an input in base 256 and encodes it to base 16 using the Hex alphabet
 * This function will not be commented.  For more info:
 * @see http://php.net/str-split
 * @see http://php.net/sprintf
 *
 * @param string $str   The value to convert
 * @return string       The base 16 rendering
 */
function base16_encode($str) {
    $byteArray = str_split($str);
    foreach ($byteArray as &$byte) {
        $byte = sprintf('%02x', ord($byte));
    }
    return join($byteArray);
}

/**
 * Takes a 22 byte crypt-base-64 hash and converts it to base 16
 * If the input is longer than 22 chars (e.g., the entire output of crypt()),
 * then this function will strip all but the last 22.  Fails if under 22 chars
 *
 * @param string $hash  The hash to convert
 * @param string        The equivalent base16 hash (therefore a number)
 */
function md5_b64tob16($hash) {
    if (strlen($hash) < 22) {
        return false;
    }
    if (strlen($hash) > 22) {
        $hash = substr($hash,-22);
    }
    return base16_encode(base64_decode_ex($hash));
}

echo md5_b64tob16($argv[1]);

?>
