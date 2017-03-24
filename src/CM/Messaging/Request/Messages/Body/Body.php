<?php

namespace CM\Messaging\Request\Messages\Body;

use CM\Messaging\Exception\InvalidConfigurationException;
use CM\Messaging\Request\RequestSerializer;
use CM\Messaging\Settings\BodyType;

/**
 * Class Body
 *
 * @package CM\Messaging\Request\Messages\Body
 */
class Body extends RequestSerializer
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $type;


    /**
     * Body constructor.
     *
     * @param               $content
     * @param null|BodyType $type
     *
     * @throws InvalidConfigurationException
     */
    public function __construct($content, $type = null)
    {
        $this->content = $content;

        if (BodyType::isValid($type)) {
            $this->type = $type;
        } elseif ($type && !BodyType::isValid($type)) {
            throw new InvalidConfigurationException("Body type `$type` is not valid.");
        }
    }

    /**
     * @return $this
     */
    protected function getClass()
    {
        return $this;
    }

}