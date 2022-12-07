<?php

namespace MondoTalk\Api;

use MondoTalk\Common\MondoTalkResourceModel;
use MondoTalk\Transport\MondoTalkRestCall;
use MondoTalk\Validation\ArgumentValidator;
use MondoTalk\Rest\ApiContext;

/**
 * Class Account
 *
 * @package MondoTalk\Api
 *
 * 
 */

class Account extends MondoTalkResourceModel
{


    /**
     * Shows you the account’s profile in detail
     *
     * @param string $account
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param MondoTalkRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Account
     */
    public static function get($account, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($account, 'account');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/profile/",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new Account();
        $ret->fromJson($json);
        return $ret;
    }
}