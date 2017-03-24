<?php

namespace CM\Messaging\Settings;

use MyCLabs\Enum\Enum;

/**
 * Class Strategy
 *
 * @package CM\Messaging\Settings
 */
class Strategy extends Enum
{
    /**
     *  This strategy will prevent the SDK form removing duplicate 'to' phone numbers on a message.
     *  Only exact matching numbers will be filtered.
     *
     */
    const KEEP_DUPLICATE_PHONE_NUMBERS = 'keep_duplicate_phone_numbers';

}