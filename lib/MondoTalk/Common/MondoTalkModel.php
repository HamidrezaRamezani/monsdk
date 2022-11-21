<?php

namespace MondoTalk\Common;

use MondoTalk\Validation\JsonValidator;


class MondoTalkModel
{
    private $_propMap = array();

    protected static $credential;

    public static function setCredential($credential)
    {
        self::$credential = $credential;
    }

}