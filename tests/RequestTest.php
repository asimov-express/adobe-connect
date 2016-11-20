<?php

namespace AsimovExpress\Testing;

use AsimovExpress\AdobeConnect\Http\Request;
use AsimovExpress\AdobeConnect\Http\InvalidMethodException;
use AsimovExpress\Testing\Concern\InteractsWithLogger;
use AsimovExpress\Testing\Concern\InteractsWithAdobeConnectApi;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    use InteractsWithAdobeConnectApi;
    use InteractsWithLogger;

    /**
     * Factory is able to create an XML request with the provided params.
     */
    public function testBuildsBasicRequestBody()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'param-1' => 'value-1'
        ]);

        $this->assertEquals(
            '<params><param name="param-1">value-1</param></params>',
            $req->getXMLBody(),
            'Wrong or malformed XML body'
        );
    }

    public function testSupportMultipleParameters()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameter('a', 'a');
        $req->addParameter('b', 'b');
        $req->addParameter('c', 'c');

        $this->assertEquals(
            '<params><param name="a">a</param><param name="b">b</param><param name="c">c</param></params>',
            $req->getXMLBody(),
            'Does not support multiple parameters'
        );
    }

    public function testSupportSpecialCharacters()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameter('a', '&');
        $req->addParameter('b', '"');
        $req->addParameter('c', '<');

        $this->assertEquals(
            '<params><param name="a">&amp;</param><param name="b">&quot;</param><param name="c">&lt;</param></params>',
            $req->getXMLBody(),
            'Does not support special charecters'
        );
    }

    public function testOverridesParameters()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c'
        ]);

        $req->addParameter('a', 'x');
        $req->addParameter('b', 'y');
        $req->addParameter('c', 'z');

        $this->assertEquals(
            '<params><param name="a">x</param><param name="b">y</param><param name="c">z</param></params>',
            $req->getXMLBody(),
            'Does overrides parameters'
        );
    }

    public function testSetsContentTypeToApplicationXML()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $headers = $req->getHeaders();

        $this->assertArraySubset(['content-type' => 'application/xml'], $headers);
    }

    public function testPerformsHttpCall()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'a' => 'b'
        ]);

        $res = $req->send();

        // Status code is 400 because we are not sending the 'action' parameter.
        $this->assertEquals(400, $res->getStatusCode());
    }

    public function testUsesLoggerWhenSet()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'a' => 'b'
        ]);

        $req->setLogger($this->logger);

        $res = $req->send();

        // Status code is 400 because we are not sending the 'action' parameter.
        $this->assertLogsGreaterThan(0, $this->logger);
    }

    public function testThrowsExceptionWithUnssuportedMethod()
    {
        $this->expectException(InvalidMethodException::class);

        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'a' => 'b'
        ]);

        $req->setMethod('GET');

        $res = $req->send();
    }
}
