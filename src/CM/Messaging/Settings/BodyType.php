<?php

namespace CM\Messaging\Settings;

use MyCLabs\Enum\Enum;

/**
 * Class BodyType
 *
 * @package CM\Messaging\Settings
 */
class BodyType extends Enum
{
    /**
     *  It possible to let our gateway do the encoding detection for you. In case it detects characters that are not part of the GSM character set, the message will be delivered as Unicode.
     *  Any existing DCS value will be ignored. If the message contains more than 70 characters in Unicode format it will be split into a multipart message.
     *
     */
    const AUTO = 'AUTO';
}