<?php
namespace AsimovExpress\Testing;

use AsimovExpress\AdobeConnect\Http\InvalidMethodException;

class InvalidMethodExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default message generated by the custom exception.
     */
    public function testInvalidMethodExceptionMessage()
    {
        $exception = new InvalidMethodException('GET');

        $this->assertEquals(
            'The HTTP method GET is not allowed in this package, use POST instead.',
            $exception->getMessage(),
            'Malformed invalid method exception message'
        );
    }
}
 ?>