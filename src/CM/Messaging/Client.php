<?php

namespace CM\Messaging;

use CM\Messaging\Exception\InvalidAllowedChannelException;
use CM\Messaging\Exception\InvalidStrategyException;
use CM\Messaging\Exception\InvalidConfigurationException;
use CM\Messaging\Http\ApiHttpClient;
use CM\Messaging\Request\Messages;
use CM\Messaging\Request\Messages\Msg;
use CM\Messaging\Settings\AllowedChannel;
use CM\Messaging\Settings\BodyType;
use CM\Messaging\Settings\Strategy;
use Http\Client\Exception\TransferException;
use Http\Client\HttpClient;

/**
 * Class Client
 *
 * @category    SDK
 * @package     CM\Messaging
 * @author      Pallieter Verhoeven <pallieter.verhoeven@cmtelecom.com>
 *
 * @link        https://www.cmtelecom.com
 */
class Client
{

    /**
     * It possible to let our gateway do the encoding detection for you. In case it detects characters that are not part of the GSM character set, the message will be delivered as Unicode.
     * Any existing DCS value will be ignored. If the message contains more than 70 characters in Unicode format it will be split into a multipart message.
     *
     * @var BodyType
     */
    private $bodyType;

    /**
     * You use the DCS (data coding scheme) parameter to indicate the type of message you are sending. If you set DCS to '0' or do not include the parameter, the messages uses standard GSM encoding.
     * If DCS is set to '8' the message will be encoded using Unicode UCS2.
     *
     * @var int
     */
    private $dcs;

    /**
     * Here you can include your message reference. This information will be returned in a status report so you can match the message and it's status. It should be included in the XML when posting.
     * Restrictions: 1 - 32 alphanumeric characters and reference will not work for demo accounts.
     *
     * @var string
     */
    private $reference;

    /**
     * Used when sending multipart or concatenated SMS messages and always used together. Indicate the minimum and maximum of message parts that you allow the gateway to send for this message.
     * Technically the gateway will first check if a message is larger than 160 characters, if so, the message will be cut into multiple 153 characters parts limited by these parameters.
     *
     * @var int
     */
    private $minimumNumberOfMessageParts;

    /**
     * Used when sending multipart or concatenated SMS messages and always used together. Indicate the minimum and maximum of message parts that you allow the gateway to send for this message.
     * Technically the gateway will first check if a message is larger than 160 characters, if so, the message will be cut into multiple 153 characters parts limited by these parameters.
     *
     * @var int
     */
    private $maximumNumberOfMessageParts;

    /**
     * Use an appKey for Hybrid Messaging purposes. If an appKey is added the gateway will deliver according the settings defined in the App Manager.
     *
     * @var string
     */
    private $appKey;

    /**
     * The allowed channels field forces a message to only use certain routes. In this field you can define a list of which channels you want your message to use. Not defining any channels will be
     * interpreted als allowing all channels.
     *
     * @var array[AllowedChannel]
     */
    private $allowedChannels = [AllowedChannel::SMS];

    /**
     * Product token to authorize with.
     *
     * @var string
     */
    private $productToken;

    /**
     * Api http client which preform requests to the server.
     *
     * @var ApiHttpClient
     */
    private $apiHttpClient;

    /**
     * Api endpoint. You don't wan't to alter this value.
     *
     * @var string
     */
    private $endpoint = 'https://gw.cmtelecom.com/v1.0/message';

    /**
     * Client constructor.
     *
     * @param string          $productToken
     * @param HttpClient|null $httpClient
     */
    public function __construct(HttpClient $httpClient, $productToken)
    {
        $this->productToken  = $productToken;
        $this->apiHttpClient = new ApiHttpClient($httpClient);
    }

    /**
     * Send message(s).
     *
     * @param array|Message $messages
     * @param array         $parameters
     *
     * @return Response\Response
     * @throws \RuntimeException
     * @throws \Http\Client\Exception
     * @throws TransferException
     * @throws InvalidConfigurationException
     * @throws InvalidStrategyException
     * @throws InvalidAllowedChannelException
     * @throws \Exception
     */
    public function send($messages, array $parameters = [])
    {
        $messages = is_array($messages) ? $messages : [$messages];

        if (isset($parameters['strategy']) && $strategies = $parameters['strategy']) {
            /** @var array $strategies */
            foreach ($strategies as $strategy) {
                if (!Strategy::isValid($strategy)) {
                    throw new InvalidStrategyException("Strategy `$strategy` is not found valid.");
                }
            }
        }

        $body = $this->buildRequest((array)$messages, $parameters);

        return $this->apiHttpClient->postRequest($this->endpoint, $body);
    }

    /**
     * @param array      $messages
     * @param array|null $parameters
     *
     * @return array
     * @throws InvalidAllowedChannelException
     * @throws InvalidConfigurationException
     */
    private function buildRequest($messages, $parameters = null)
    {
        $request = new Messages();

        $request->setAuthentication($this->productToken);

        foreach ($messages as $message) {
            $msg = new Msg();
            $msg->setFrom($message->getFrom())
                ->setTo($message->getTo(), $parameters)
                ->setBody($message->getBody(), $message->getBodyType() ?: $this->getBodyType())
                ->setDcs($message->getDcs() ?: $this->getDcs())
                ->setReference($message->getReference() ?: $this->getReference())
                ->setMinimumNumberOfMessageParts($message->getMinimumNumberOfMessageParts() ?: $this->getMinimumNumberOfMessageParts())
                ->setMaximumNumberOfMessageParts($message->getMaximumNumberOfMessageParts() ?: $this->getMaximumNumberOfMessageParts())
                ->setAppKey($message->getAppKey() ?: $this->getAppKey())
                ->setAllowedChannels($message->getAllowedChannels() ?: $this->getAllowedChannels());

            $request->addMsg($msg);
        }

        return ['messages' => $request];
    }

    /**
     * @return BodyType
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @param BodyType $bodyType
     *
     * @return $this
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * @return int
     */
    public function getDcs()
    {
        return $this->dcs;
    }

    /**
     * @param $dcs
     *
     * @return $this
     */
    public function setDcs($dcs)
    {
        $this->dcs = $dcs;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumNumberOfMessageParts()
    {
        return $this->minimumNumberOfMessageParts;
    }

    /**
     * @param $minimumNumberOfMessageParts
     *
     * @return $this
     */
    public function setMinimumNumberOfMessageParts($minimumNumberOfMessageParts)
    {
        $this->minimumNumberOfMessageParts = $minimumNumberOfMessageParts;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumNumberOfMessageParts()
    {
        return $this->maximumNumberOfMessageParts;
    }

    /**
     * @param $maximumNumberOfMessageParts
     *
     * @return $this
     */
    public function setMaximumNumberOfMessageParts($maximumNumberOfMessageParts)
    {
        $this->maximumNumberOfMessageParts = $maximumNumberOfMessageParts;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @param $appKey
     *
     * @return $this
     */
    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedChannels()
    {
        return $this->allowedChannels;
    }

    /**
     * @param array|AllowedChannel $allowedChannels
     *
     * @return $this
     */
    public function setAllowedChannels($allowedChannels)
    {
        $this->allowedChannels = $allowedChannels;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductToken()
    {
        return $this->productToken;
    }

}
