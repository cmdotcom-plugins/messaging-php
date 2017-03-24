<?php

namespace CM\Messaging\Request\Messages\To;

use CM\Messaging\Request\RequestSerializer;

/**
 * Class Number
 *
 * @package CM\Messaging\Request\Messages\To
 */
class Number extends RequestSerializer
{
    /**
     * @var int
     */
    protected $number;

    /**
     * Number constructor.
     *
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * @return $this
     */
    protected function getClass()
    {
        return $this;
    }

}