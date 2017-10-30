<?php

namespace CM\Messaging;

use CM\Messaging\Settings\AllowedChannel;
use CM\Messaging\Settings\BodyType;

/**
 * Class Messaging
 *
 * @package CM\Messaging
 */
class Message
{
    /**
     * Required. This is the sender name. The maximum length is 11 alphanumerical characters or 16 digits. Example: 'CM Telecom'
     *
     * @var string
     */
    private $from;

    /**
     * Required. This is the destination mobile number. Restrictions: this value should be in international format. Example: '00447911123456'
     *
     * @var array
     */
    private $to;

    /**
     * Required. This is the message text.
     *
     * @var string
     */
    private $body;

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
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    private $customGrouping1;

    /**
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    private $customGrouping2;

    /**
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    private $customGrouping3;

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
    private $allowedChannels;

    /**
     * Messaging constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param array|string $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = (array)$to;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string        $body
     * @param null|BodyType $type
     *
     * @return $this
     */
    public function setBody($body, $type = null)
    {
        $this->body     = $body;
        $this->bodyType = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
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
     * Get your message custom grouping.
     *
     * @return string
     */
    public function getCustomGrouping1()
    {
        return $this->customGrouping1;
    }

    /**
     * @param $customGrouping
     *
     * @return $this
     */
    public function setCustomGrouping1($customGrouping)
    {
        $this->customGrouping1 = $customGrouping;

        return $this;
    }

    /**
     * Get your message custom grouping.
     *
     * @return string
     */
    public function getCustomGrouping2()
    {
        return $this->customGrouping2;
    }

    /**
     * @param $customGrouping
     *
     * @return $this
     */
    public function setCustomGrouping2($customGrouping)
    {
        $this->customGrouping2 = $customGrouping;

        return $this;
    }

    /**
     * Get your message custom grouping.
     *
     * @return string
     */
    public function getCustomGrouping3()
    {
        return $this->customGrouping3;
    }

    /**
     * @param $customGrouping
     *
     * @return $this
     */
    public function setCustomGrouping3($customGrouping)
    {
        $this->customGrouping3 = $customGrouping;

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
        $this->allowedChannels = (array)$allowedChannels;

        return $this;
    }

}