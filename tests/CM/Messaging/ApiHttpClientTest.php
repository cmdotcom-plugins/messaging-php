<?php

namespace Test\CM\Messaging;

use CM\Messaging\Config;
use CM\Messaging\Exception\BadRequestException;
use CM\Messaging\Http\ApiHttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\TransferException;
use Http\Mock\Client as MockClient;

class ApiHttpClientTest extends TestCase
{
    /**
     * @var MockClient
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = new MockClient();
    }

    public function testBuildHeader()
    {
        $expected   = [
            'Content-Type' => 'application/json',
            'LANGUAGE'     => 'PHP',
            'SDK_VERSION'  => Config::SDK_VERSION,
            'HTTP_CLIENT'  => 'Http\\Mock\\Client'
        ];

        $client = new ApiHttpClient($this->client);
        $header = $this->invokeMethod($client, 'buildHeader');

        $this->assertArraySubset($expected, $header);
    }

    /**
     * @dataProvider postRequestResponseProvider
     */
    public function testPostRequestResponse($response, $isAccepted)
    {
        $this->client->addResponse($response);

        $client = new ApiHttpClient($this->client);
        $result = $client->postRequest($this->getMockMessage(1));

        $this->assertInstanceOf('CM\Messaging\Response\Response', $result);
        $this->assertEquals(200, $result->getResponse()->getStatusCode());
        $this->assertSame($isAccepted, $result->isAccepted());
    }

    /**
     * @expectedException \CM\Messaging\Exception\BadRequestException
     */
    public function testPostRequestBadRequest()
    {
        $request           = new Request('POST', '');
        $response          = $this->client->sendRequest($request);
        $responseException = new BadRequestException(null, $request, $response);

        $this->client->addException($responseException);

        $client = new ApiHttpClient($this->client);
        $client->postRequest($this->getMockMessage(1, false));
    }

    /**
     * @expectedException \Http\Client\Exception\HttpException
     */
    public function testPostRequestHttpException()
    {
        $request           = new Request('POST', '');
        $response          = $this->client->sendRequest($request);
        $responseException = new HttpException(null, $request, $response);

        $this->client->addException($responseException);

        $client = new ApiHttpClient($this->client);
        $client->postRequest($this->getMockMessage(1));
    }

    /**
     * @expectedException \Http\Client\Exception\TransferException
     */
    public function testPostRequestTransferException()
    {
        $responseException = new TransferException(null, null, null);
        $this->client->addException($responseException);

        $client = new ApiHttpClient($this->client);
        $client->postRequest($this->getMockMessage(1));
    }

    public function postRequestResponseProvider()
    {
        return [
            [new Response(200, [], file_get_contents(__DIR__ . '/Mocks/validResponseContent.json')), true],
            [new Response(200, [], file_get_contents(__DIR__ . '/Mocks/validResponseContentMultipleWithFailed.json')), false]
        ];
    }
}
