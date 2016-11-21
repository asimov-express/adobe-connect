<?php
namespace AsimovExpress\Testing;

use AsimovExpress\AdobeConnect\Http\Request;
use AsimovExpress\Testing\Concern\InteractsWithAdobeConnectApi;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    use InteractsWithAdobeConnectApi;
    /**
     * Response returns content as SimpleXMLElement object.
     */
    public function testResponseReturnsSimpeXMLElementObject()
    {
        $req = new Request(static::getAdobeConnectApiUri());

        $req->addParameters([
            'action' => 'login',
            'login' => 'fakeuser@example.com',
            'password' => 'secret'
        ]);

        $res = $req->send();

        $xml = $res->getContentAsXml();

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
