<?php

namespace MondoTalk\Validation;

/**
 * Class JsonValidator
 *
 * @package MondoTalk\Validation
 */
class JsonValidator
{

    /**
     * The string provided can be validated as a valid JSON using this helper method.
     *
     * @param string $string String representation of Json object
     * @param bool $silent Flag to not throw \InvalidArgumentException
     * @return bool
     */
    public static function validate($string, $silent = false)
    {
        @json_decode($string);
        if (json_last_error() != JSON_ERROR_NONE) {
            if ($string === '' || $string === null) {
                return true;
            }
            if ($silent == false) {
                //Throw an Exception for string or array
                throw new \InvalidArgumentException("Invalid JSON String");
            }
            return false;
        }
        return true;
    }
}
