<?php

namespace Rrim\PhpUserManagement\Log;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Rrim\PhpUserManagement\Model\UserRegisterResponse;
use Rrim\PhpUserManagement\Service\UserService;

class Log


{

    protected Logger $logger;
    private StreamHandler $streamHandler;


    private function loggerFormat() : void
    {
        $logger = new $this->logger();

    }


}