<?php

namespace Test\CM\Messaging;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as MockClient;

class SuccessTest extends TestCase
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
        $acceptedPhoneNumbers = ['0031612345678'];

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertTrue($result->isAccepted());
        $this->assertTrue($result->isAccepted($acceptedPhoneNumbers));
        $this->assertCount(1, $result->getAccepted());
        $this->assertCount(1, $result->getAccepted($acceptedPhoneNumbers));

        $this->assertNull($result->getFailed());
        $this->assertNull($result->getFailed($acceptedPhoneNumbers));
    }

    public function testAllAcceptedMultiple()
    {
        $response             = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContentMultiple.json'));
        $acceptedPhoneNumbers = ['0031612345678', '0031623456789', '0031634567890'];

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertTrue($result->isAccepted());
        $this->assertTrue($result->isAccepted($acceptedPhoneNumbers));
        $this->assertCount(3, $result->getAccepted());
        $this->assertCount(3, $result->getAccepted($acceptedPhoneNumbers));

        $this->assertNull($result->getFailed());
        $this->assertNull($result->getFailed($acceptedPhoneNumbers));
    }

    public function testAllSomeAcceptedMultiple()
    {
        $response             = new Response(200, [], file_get_contents(__DIR__ . '/../Mocks/validResponseContentMultipleWithFailed.json'));
        $acceptedPhoneNumbers = ['0031612345678', '0031623456789'];
        $failedPhoneNumbers   = ['0031634567890'];

        $mockClient = new MockClient();
        $mockClient->addResponse($response);
        $result = new \CM\Messaging\Response\Response($response);

        $this->assertFalse($result->isAccepted());
        $this->assertCount(2, $result->getAccepted());
        $this->assertCount(2, $result->getAccepted($acceptedPhoneNumbers));

        $this->assertCount(1, $result->getFailed());
    }
}