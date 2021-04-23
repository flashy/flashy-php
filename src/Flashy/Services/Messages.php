<?php

namespace Flashy\Services;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy;
use Flashy\Response;

class Messages {

    /**
     * @var Flashy
     */
    protected $flashy;

    /**
     * Lists constructor.
     * @param $flashy
     */
    public function __construct($flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function email($message = [])
    {
        $this->validateMessage($message);

//        if( !isset($message['to']) || gettype($message['to']) != "array" || count($message['to']) === 0 )
//            throw new FlashyException("Message [to] is empty or invalid");

        return $this->flashy->client->post("messages/email");
    }

    /**
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function sms($message = [])
    {
        $this->validateMessage($message);

        if( !isset($message['to']) )
            throw new FlashyException("Message [to] is empty");

        return $this->flashy->client->post("messages/sms");
    }

    /**
     * @param $message
     * @throws FlashyException
     */
    public function validateMessage($message)
    {
        if( !$message || count($message) === 0 || gettype($message) != "array" )
            throw new FlashyException("Message body is invalid");
    }

}
