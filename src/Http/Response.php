<?php
namespace AsimovExpress\AdobeConnect\Http;

class Response
{
    protected $statusCode;

    public function __construct($curl_resource, $result)
    {
        $info = curl_getinfo($curl_resource);
        $this->statusCode = $info['http_code'];
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
