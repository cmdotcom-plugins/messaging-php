<?php

namespace CM\Messaging\Request;

/**
 * Class RequestSerializer
 *
 * @package CM\Messaging\Request
 */
abstract class RequestSerializer implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON and filter all null values
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this->getClass()));
    }

    /**
     * @return $this
     */
    abstract protected function getClass();

}