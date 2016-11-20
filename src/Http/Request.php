<?php

namespace AsimovExpress\AdobeConnect\Http;

use XMLWriter;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use AsimovExpress\AdobeConnect\Http\InvalidMethodException;

class Request implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Parameters to be send in the request.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Headers to be send most likely to keep unchanged.
     *
     * @var array
     */
    protected $headers;

    /**
     * Adobe Connect API path
     *
     * @var string
     */
    protected $apiPath;

    /**
     * HTTP method to perform the request to the Adobe Connect API.
     * Adobe Connect only supports 'POST' and 'GET' methods.
     *
     * @var string
     */
    protected $method = 'POST';

    /**
     * Request constructor
     *
     * @param string $apiPath Path of the Adobe Connect API.
     * @param array $parameters Parameters to be send to the API.
     * @param array $headers Custom headers to be send to the API.
     */
    public function __construct($apiPath, $parameters = [], $headers = [])
    {
        $this->apiPath = $apiPath;
        $this->parameters = $parameters;
        $this->headers = array_merge([
            'content-type' => 'application/xml'
        ], $headers);
    }

    /**
     * Adds a list of params to the request.
     *
     * @param array $parameters Hash table with parameters to be added.
     *
     * @return void
     */
    public function addParameters($parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * Adds a single parameter.
     *
     * @return void
     */
    public function addParameter($parameter, $value)
    {
        $this->addParameters([$parameter => $value]);
    }

    /**
     * Return the request body to be sent to the Adobe Connect API.
     *
     * @return string
     */
    public function getXMLBody()
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startElement('params');
        foreach ($this->parameters as $parameter => $value) {
            $writer->startelement('param');
            $writer->startAttribute('name');
            $writer->text($parameter);
            $writer->endAttribute();
            $writer->text($value);
            $writer->endElement();
        }
        $writer->endElement();
        $xml = $writer->outputMemory();
        $writer->flush();
        return $xml;
    }

    /**
     * Returns all headers set for the request.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets a different HTTP method.
     *
     * @param string $method The http method to be used in API calls.
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Calls the remote Adobe Connect API.
     *
     * @return Response
     */
    public function send()
    {
        if ($this->method === 'POST') {
            return $this->post();
        }

        throw new InvalidMethodException($this->method);
    }

    /**
     * Calls the remote Adobe Connect API using HTTP POST method.
     *
     * @return Response
     */
    public function post()
    {
        $resource = curl_init();
        $xml = $this->getXMLBody();

        curl_setopt($resource, CURLOPT_URL, $this->apiPath);
        curl_setopt($resource, CURLOPT_POST, true);
        foreach ($this->headers as $header => $value) {
            curl_setopt($resource, CURLOPT_HTTPHEADER, [$header, $value]);
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($resource);

        if ($this->logger) {
            $this->logger->info('AdobeConnect request using POST method', [
                'url' => $this->apiPath,
                'headers' => $this->headers,
                'post_body' => $xml
            ]);
        }

        return new Response($resource, $result);
    }
}
