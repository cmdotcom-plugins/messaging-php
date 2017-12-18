<?php

namespace CM\Messaging\Settings;

use MyCLabs\Enum\Enum;

/**
 * Class AllowedChannel
 *
 * @package CM\Messaging\Settings
 */
class AllowedChannel extends Enum
{
    /**
     *  Allow to send message by SMS
     */
    const SMS = 'SMS';

    /**
     *  Allow to send message by Push
     */
    const PUSH = 'Push';

    /**
     *  Allow to send message by Voice
     */
    const VOICE = 'Voice';
}