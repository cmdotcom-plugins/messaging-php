<?php

namespace Test\CM\Messaging;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as MockClient;

class ResponseTest extends TestCase
{

    public function testInstantiation()
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContent.json'));

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertInstanceOf('CM\Messaging\Response\Response', $result);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $result->getResponse());
    }

    public function testAllAcceptedOne()
    {
        $response             = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContent.json'));

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertTrue($result->isAccepted());
        $this->assertCount(1, $result->getAccepted());

        $this->assertCount(0, $result->getFailed());
    }

    public function testAllAcceptedMultiple()
    {
        $response             = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContentMultiple.json'));

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertTrue($result->isAccepted());
        $this->assertCount(3, $result->getAccepted());

        $this->assertCount(0, $result->getFailed());
    }

    public function testAllSomeAcceptedMultiple()
    {
        $response             = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContentMultipleWithFailed.json'));

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertFalse($result->isAccepted());
        $this->assertCount(2, $result->getAccepted());

        $this->assertCount(1, $result->getFailed());
    }
}
