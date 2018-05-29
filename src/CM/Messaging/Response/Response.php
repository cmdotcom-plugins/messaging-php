<?php

namespace CM\Messaging\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * @package CM\Messaging\Response
 */
class Response
{
    /**
     * Original response of the request formatted in PSR-7 standard
     *
     * @var ResponseInterface PSR-7
     */
    private $response;

    /**
     * Status message from the server response
     *
     * @var string
     */
    private $details;

    /**
     * Messaging(s) accepted by the server
     *
     * @var array
     */
    private $accepted = array();

    /**
     * Messaging(s) rejected by te server
     *
     * @var array
     */
    private $failed = array();

    /**
     * Response constructor.
     *
     * @param ResponseInterface $response
     *
     * @throws \RuntimeException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseObject = json_decode($response->getBody()->getContents());

        $response->getBody()->rewind();

        $this->response = $response;
        $this->details  = $responseObject->details;

        foreach ((array)$responseObject->messages as $message) {
            if ($message->status === 'Accepted') {
                $this->accepted[] = $message;
            } else {
                $this->failed[] = $message;
            }
        }
    }

    /**
     * Get original response of the request formatted in PSR-7 standard
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get status message from the server response
     *
     * @return ResponseInterface
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Get message(s) accepted by the server.
     *
     * @return array
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Get bool if message(s) accepted by the server.
     *
     * @return bool
     */
    public function isAccepted()
    {
        return count($this->failed) === 0;
    }

    /**
     * Get messages rejected by the server.
     *
     * @return array
     */
    public function getFailed()
    {
        return $this->failed;
    }
}
