<?php

namespace CM\Messaging\Request;

use CM\Messaging\Request\Messages\Authentication;
use CM\Messaging\Request\Messages\Msg;

/**
 * Class Messages
 *
 * @package CM\Messaging\Request
 */
class Messages extends RequestSerializer
{
    /**
     * @var Authentication
     */
    protected $authentication;

    /**
     * @var Msg
     */
    protected $msg;

    /**
     * @return Authentication
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param $productToken
     */
    public function setAuthentication($productToken)
    {
        $this->authentication = new Authentication($productToken);
    }

    /**
     * @return Msg
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param $msg
     */
    public function addMsg($msg)
    {
        $this->msg[] = $msg;
    }

    /**
     * Get current class context
     *
     * @return $this
     */
    protected function getClass()
    {
        return $this;
    }
}