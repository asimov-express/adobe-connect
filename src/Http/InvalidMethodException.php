<?php
namespace AsimovExpress\AdobeConnect\Http;

class InvalidMethodException extends \Exception
{
    /**
     * The http method used in the request.
     *
     * @var string
     */
    protected $method;

    /**
     * Create a new InvalidMethodException instance.
     *
     * @param string $method The http method intended to use.
     * @param string $message Custom message to be shown.
     * @param int $code Exception code.
     */
    public function __construct($method, $message = '', $code = 0)
    {
        $this->method = $method;
        if (!$message) {
            $message = "The HTTP method {$this->method} is not allowed in this package, use POST instead.";
        }
        parent::__construct($message, $code);
    }
}
