<?php

namespace CM\Messaging\Request\Messages;

use CM\Messaging\Request\RequestSerializer;

/**
 * Class Authentication
 *
 * @package CM\Messaging\Request\Messages
 */
class Authentication extends RequestSerializer
{
    /**
     * Product token to authorize with
     *
     * @var string
     */
    protected $productToken;

    /**
     * Authentication constructor.
     *
     * @param $productToken
     */
    public function __construct($productToken)
    {
        $this->productToken = $productToken;
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