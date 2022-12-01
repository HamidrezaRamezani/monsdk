<?php

namespace MondoTalk\Exception;

/**
 * Class MondoTalkConfigurationException
 *
 * @package MondoTalk\Exception
 */
class MondoTalkConfigurationException extends \Exception
{

    /**
     * Default Constructor
     *
     * @param string|null $message
     * @param int  $code
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
