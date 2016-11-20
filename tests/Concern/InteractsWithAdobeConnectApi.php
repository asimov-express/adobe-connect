<?php
namespace AsimovExpress\Testing\Concern;

use AsimovExpress\Testing\Dummy\DummyApiServer;

trait InteractsWithAdobeConnectApi
{
    /**
     * Instance of DummyApiServer
     * @var DummyApiServer
     */
    public static $apiServer;

    /**
     * Hostname on which the server will run.
     */
    public static $host = 'localhost';

    /**
     * Port on which the server will listen.
     */
    public static $port = 8003;

    /**
     * Server's root folder.
     */
    public static $root = __DIR__.'/adobe-connect-server';

    /**
     * Starts the dummy API server
     *
     * @beforeClass
     */
    public static function initializeApiServer()
    {
        static::$apiServer = new DummyApiServer(
            static::$host,
            static::$port,
            static::$root
        );
        static::$apiServer->start();
    }

    /**
     * Shouts down the API Server
     *
     * @afterClass
     */
    public static function shoutdownApiServer()
    {
        static::$apiServer->stop();
    }

    /**
     * Return the Adobe Connect API URI
     */
    public static function getAdobeConnectApiUri()
    {
        $host = static::$host;
        $port = static::$port;
        return "http://{$host}:{$port}/api/xml";
    }
}
