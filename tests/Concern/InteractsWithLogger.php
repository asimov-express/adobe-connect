<?php
namespace AsimovExpress\Testing\Concern;

use AsimovExpress\Testing\Dummy\DummyLogger;

trait InteractsWithLogger
{
    /**
     * Logger to be use in testing.
     *
     * @var DummyLogger
     */
    protected $logger;

    /**
     * Creates a new logger instance to be used in the test.
     *
     * @before
     */
    public function initializeLogger()
    {
        $this->logger = new DummyLogger;
    }

    public static function assertLogsGreaterThan($count, DummyLogger $logger, $message = '')
    {
        if (!$message) {
            $message = "Exepected more than $count log messages but {$logger->count()} were found";
        }
        self::assertThat($logger->count(), self::greaterThan($count), $message);
    }

    public static function assertLogsCount($count, DummyLogger $logger, $message = '')
    {
        if (!$message) {
            $message = "Exepected $count log messages but {$logger->count()} were found";
        }
        self::assertThat($logger->count(), self::count($count), $message);
    }
}
