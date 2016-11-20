<?php
namespace AsimovExpress\Testing\Dummy;

use Symfony\Component\Process\Process;

/**
 * Creates a web server listening in the specified port.
 * This class is meant to fake api servers.
 */
class DummyApiServer
{
    /**
     * Process instance.
     */
    protected $process;

    /**
     * Hostname to be use.
     */
    protected $host = 'localhost';

    /**
     * Port on which the server will listen.
     */
    protected $port = 8000;

    /**
     * Server's root folder.
     */
    protected $root = __DIR__.'/server';

    /**
     * Creates a server instance.
     */
    public function __construct($host, $port, $root)
    {
        if ($host) {
            $this->host = $host;
        }
        if ($port) {
            $this->port = $port;
        }
        if ($root) {
            $this->root = $root;
        }
    }

    /**
     * Starts the server with the specified parameters.
     */
    public function start()
    {
        $command = "php -S {$this->host}:{$this->port} -t {$this->root}";
        $this->process = new Process($command);
        $this->process->start();
    }

    /**
     * Stops the running server.
     */
    public function stop()
    {
        if ($this->process->isRunning()) {
            $this->process->stop();
        }
    }

    /**
     * Sets a new hostname.
     *
     * @param string $host Hostname on which the server will run.
     */
    public function setHost($host)
    {
        if ($this->process && $this->process->isRunning()) {
            throw new \Exception('The host name can not be modified while the server is running.');
        }
        $this->host = $host;
    }
}
