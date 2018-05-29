<?php

namespace Test\CM\Messaging;

use CM\Messaging\Client;
use CM\Messaging\Exception\InvalidStrategyException;
use CM\Messaging\Request\Messages;
use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Http\Mock\Client as MockClient;

/**
 * Class ClientTest
 *
 * @package Test\CM\Messaging
 */
class ClientTest extends TestCase
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $productToken;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->httpClient   = new MockClient();
        $this->productToken = $this->faker->uuid;
    }

    /**
     * @expectedException \CM\Messaging\Exception\InvalidStrategyException
     */
    public function testSendInvalidStrategy()
    {
        $messageCount = 1;
        $messages     = $this->getMockMessage($messageCount);

        $client = new Client($this->httpClient, $this->productToken);
        $client->send($messages, ['strategy' => ['invalid_strategy_param']]);
    }

    public function testSend()
    {
        $mockClient   = new MockClient();
        $response     = new Response(200, [], file_get_contents(__DIR__ . '/Mocks/validResponseContent.json'));
        $messageCount = 1;
        $messages     = $this->getMockMessage($messageCount);

        $mockClient->addResponse($response);
        $client = new Client($mockClient, $this->productToken);

        /** @var \CM\Messaging\Response\Response $result */
        $result = $client->send($messages);

        $this->assertInstanceOf('\CM\Messaging\Response\Response', $result);
        $this->assertCount(1, $result->getAccepted());
        $this->assertCount(0, $result->getFailed());
    }

    public function testSendBuildRequest()
    {
        $messageCount = 2;
        $messages     = $this->getMockMessage($messageCount);

        $client = new Client($this->httpClient, $this->productToken);

        /** @var Messages $result */
        $result = $this->invokeMethod($client, 'buildRequest', [$messages])['messages'];

        $this->validateMessagesRequest($result, $messageCount);
    }

    /**
     * @param Messages $request
     * @param int      $messageCount
     */
    private function validateMessagesRequest(Messages $request, $messageCount = 1)
    {
        for ($i = 0; $i < $messageCount; $i++) {
            $this->assertInstanceOf('CM\Messaging\Request\Messages', $request);
            $this->assertInstanceOf('CM\Messaging\Request\Messages\Authentication', $request->getAuthentication());
            $this->assertInstanceOf('CM\Messaging\Request\Messages\Msg', $request->getMsg()[$i]);
            //check of all phone numbers are formatted as Messages\To\Number
            $countNumbers = count($request->getMsg()[$i]->getTo());
            for ($iTo = 0; $iTo < $countNumbers; $iTo++) {
                $this->assertInstanceOf('CM\Messaging\Request\Messages\To\Number', $request->getMsg()[$i]->getTo()[$iTo]);
            }

            $this->assertInstanceOf('CM\Messaging\Request\Messages\Body\Body', $request->getMsg()[$i]->getBody());
        }
    }

}
