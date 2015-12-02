<?php

namespace RonRademaker\DependencyInjectionService\Tests\Fixtures;

use Psr\Log\LoggerInterface;

class MyObject implements LoggerAwareInterface
{
    private $logger;
    private static $staticLogger;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function staticLogger(LoggerInterface $logger)
    {
        self::$staticLogger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public static function getStaticLogger()
    {
        return self::$staticLogger;
    }
}
