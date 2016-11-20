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
        $command = $this->makeServerCommand();
        $this->process = new Process($command);
        $this->process->start();
        usleep(500000);
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

    /**
     * Uses the current information to create the server command.
     *
     * @return string
     */
    public function makeServerCommand()
    {
        $command = "";
        if ($this->isHHVM()) {
            $command = "hhvm -m server"
                . " -d hhvm.server.type=proxygen"
                . " -d hhvm.server.port={$this->port}"
                . " -d hhvm.server.source_root={$this->root}"
                . " -d hhvm.server.default_document = index.php";
        } else {
            $command = "php -S {$this->host}:{$this->port} -t {$this->root}";
        }

        return $command;
    }

    /**
     * Return true if running on HHVM.
     *
     * @return bool
     */
    public function isHHVM()
    {
        return defined('HHVM_VERSION');
    }
}
