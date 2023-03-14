<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\MockWebServer;
use PHPUnit\Framework\TestCase;

/**
 * Test base for Chullo to setup the mock webserver.
 */
class ChulloTestBase extends TestCase
{

    protected static $webserver;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$webserver = new MockWebServer();
        self::$webserver->start();
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        self::$webserver->stop();
    }
}
