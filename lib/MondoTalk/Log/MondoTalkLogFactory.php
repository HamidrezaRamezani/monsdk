<?php

namespace MondoTalk\Log;

use Psr\Log\LoggerInterface;

interface MondoTalkLogFactory
{
    /**
     * Returns logger instance implementing LoggerInterface.
     *
     * @param string $className
     * @return LoggerInterface instance of logger object implementing LoggerInterface
     */
    public function getLogger($className);
}