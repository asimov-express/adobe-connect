<?php
namespace AsimovExpress\Testing\Dummy;

use Psr\Log\AbstractLogger;

class DummyLogger extends AbstractLogger
{
    /**
     * Keeps a copy of all the logs reported.
     *
     * @var array
     */
    protected $logs;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];
    }

    /**
     * Returns the number of logs registered.
     *
     * @return int
     */
    public function count()
    {
        return count($this->logs);
    }

    /**
     * Removes all log messages previously saved.
     *
     * @return void
     */
    public function clear()
    {
        $this->logs = [];
    }
}
