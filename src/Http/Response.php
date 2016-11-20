<?php
namespace AsimovExpress\AdobeConnect\Http;

class Response
{
    /**
     * Http status code received.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Http response body.
     *
     * @var string
     */
    protected $content;

    /**
     * Initilizes an instance of Response.
     *
     * @param resource $curl_resource Curl resource instance.
     * @param string $result Response content.
     */
    public function __construct($curl_resource, $content)
    {
        $info = curl_getinfo($curl_resource);
        $this->statusCode = $info['http_code'];
        $this->content = $content;
    }

    /**
     * Http status code received from the server.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Http response body.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
