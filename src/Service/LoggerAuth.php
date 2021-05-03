<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class LoggerAuth
{
    /** @var LoggerInterface  */
    private $logger;

    /**
     * LoggerAuth constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function loggerAuth($firstname, $lastname, $email)
    {
        $this->logger->info($firstname . ' ' . $lastname . ' s\' est connecté avec son adresse courriel ' . $email);
    }

}