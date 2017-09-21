<?php

namespace CM\Messaging\Request\Messages;

use CM\Messaging\Exception\InvalidAllowedChannelException;
use CM\Messaging\Request\Messages\Body\Body;
use CM\Messaging\Request\Messages\To\Number;
use CM\Messaging\Request\RequestSerializer;
use CM\Messaging\Settings\AllowedChannel;

/**
 * Class Msg
 *
 * @package CM\Messaging\Request\Messages
 */
class Msg extends RequestSerializer
{

    /**
     * Required. This is the sender name. The maximum length is 11 alphanumerical characters or 16 digits. Example: 'CM Telecom'.
     *
     * @var string
     */
    protected $from;

    /**
     * Required. This is the destination mobile number. Restrictions: this value should be in international format. Example: '00447911123456'.
     *
     * @var array
     */
    protected $to;

    /**
     * Required. This is the message text.
     *
     * @var string
     */
    protected $body;

    /**
     * You use the DCS (data coding scheme) parameter to indicate the type of message you are sending. If you set DCS to '0' or do not include the parameter, the messages uses standard GSM encoding.
     * If DCS is set to '8' the message will be encoded using Unicode UCS2.
     *
     * @var int
     */
    protected $dcs;

    /**
     * Here you can include your message reference. This information will be returned in a status report so you can match the message and it's status. It should be included in the XML when posting.
     * Restrictions: 1 - 32 alphanumeric characters and reference will not work for demo accounts.
     *
     * @var string
     */
    protected $reference;

    /**
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    protected $customGrouping;

    /**
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    protected $customGrouping2;

    /**
     * Applying custom grouping names to messages helps filter your messages. With up to three levels of custom grouping fields that can be set, subsets of messages can be further broken down.
     * The custom grouping name can be up to 100 characters of your choosing. It’s recommended to limit the number of unique custom groupings to 1000.
     *
     * @var string
     */
    protected $customGrouping3;

    /**
     * Used when sending multipart or concatenated SMS messages and always used together. Indicate the minimum and maximum of message parts that you allow the gateway to send for this message.
     * Technically the gateway will first check if a message is larger than 160 characters, if so, the message will be cut into multiple 153 characters parts limited by these parameters.
     *
     * @var int
     */
    protected $minimumNumberOfMessageParts;

    /**
     * Used when sending multipart or concatenated SMS messages and always used together. Indicate the minimum and maximum of message parts that you allow the gateway to send for this message.
     * Technically the gateway will first check if a message is larger than 160 characters, if so, the message will be cut into multiple 153 characters parts limited by these parameters.
     *
     * @var int
     */
    protected $maximumNumberOfMessageParts;

    /**
     * Use an appKey for Hybrid Messaging purposes. If an appKey is added the gateway will deliver according the settings defined in the App Manager.
     *
     * @var string
     */
    protected $appKey;

    /**
     * The allowed channels field forces a message to only use certain routes. In this field you can define a list of which channels you want your message to use. Not defining any channels will be
     * interpreted als allowing all channels.
     *
     * @var array
     */
    protected $allowedChannels;


    /**
     * Set sender name.
     *
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
     * Set the destination phone number(s).
     *
     * @param array $toArray
     * @param array $parameters
     *
     * @return $this
     */
    public function setTo($toArray, $parameters = [])
    {
        if (!(isset($parameters['strategy']) && in_array('keep_duplicate_phone_numbers', $parameters['strategy'], false))) {
            $toArray = array_unique($toArray);
        }

        $numbers = [];
        foreach ($toArray as $number) {
            $numbers[] = new Number($number);
        }
        $this->to = $numbers;

        return $this;
    }

    /**
     * Set the DCS (data coding scheme).
     *
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
     * Set the minimum message parts
     *
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
     * Set the maximum message parts.
     *
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
     * Set appKey to use Hybrid Messaging.
     *
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
     * Set allowed channels field to force a message to only use certain routes.
     *
     * @param array $allowedChannels
     *
     * @return $this
     * @throws InvalidAllowedChannelException
     */
    public function setAllowedChannels($allowedChannels)
    {
        /** @var array $allowedChannels */
        foreach ($allowedChannels as $allowedChannel) {
            if (!AllowedChannel::isValid($allowedChannel)) {
                throw new InvalidAllowedChannelException();
            }

            $this->allowedChannels = (array)$allowedChannels;
        }

        return $this;
    }

    /**
     * @param      $body
     * @param null $type
     *
     * @return $this
     * @throws \CM\Messaging\Exception\InvalidConfigurationException
     */
    public function setBody($body, $type = null)
    {
        $this->body = new Body($body, $type);

        return $this;
    }

    /**
     * Set your message reference.
     *
     * @return $this
     *
     * @param $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Set your message custom grouping.
     *
     * @param $customGrouping1
     *
     * @return $this
     */
    public function setCustomGrouping1($customGrouping1)
    {
        $this->customGrouping = $customGrouping1;

        return $this;
    }

    /**
     * Set your message reference.
     *
     * @param $customGrouping2
     *
     * @return $this
     */
    public function setCustomGrouping2($customGrouping2)
    {
        $this->customGrouping2 = $customGrouping2;

        return $this;
    }

    /**
     * Set your message reference.
     *
     * @param $customGrouping3
     *
     * @return $this
     */
    public function setCustomGrouping3($customGrouping3)
    {
        $this->customGrouping3 = $customGrouping3;

        return $this;
    }

    /**
     * Get sender name.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get the destination phone number(s).
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Get the DCS (data coding scheme).
     *
     * @return int
     */
    public function getDcs()
    {
        return $this->dcs;
    }

    /**
     * Get the minimum message parts.
     *
     * @return int
     */
    public function getMinimumNumberOfMessageParts()
    {
        return $this->minimumNumberOfMessageParts;
    }

    /**
     * Get the maximum message parts.
     *
     * @return int
     */
    public function getMaximumNumberOfMessageParts()
    {
        return $this->maximumNumberOfMessageParts;
    }

    /**
     * Get appKey to use Hybrid Messaging.
     *
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * Get allowed channels field to force a message to only use certain routes.
     *
     * @return array
     */
    public function getAllowedChannels()
    {
        return $this->allowedChannels;
    }

    /**
     * Get the message text.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get your message reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Get your message custom grouping.
     *
     * @return string
     */
    public function getCustomGrouping1()
    {
        return $this->customGrouping;
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
     * Get your message custom grouping.
     *
     * @return string
     */
    public function getCustomGrouping3()
    {
        return $this->customGrouping3;
    }

    /**
     * Get current class context.
     *
     * @return $this
     */
    protected function getClass()
    {
        return $this;
    }

}