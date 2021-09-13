<?php

namespace Flashy\Services;

use Flashy\Exceptions\FlashyAuthenticationException;
use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy;
use Flashy\Response;

class Messages {
    //TODO - go over validation and bulk sending

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
     * @param array $message
     * @return Response
     * @throws FlashyClientException|FlashyResponseException|FlashyAuthenticationException|FlashyException
     */
    public function email($message = [])
    {
        $this->validateMessage($message);

//        if( !isset($message['to']) || gettype($message['to']) != "array" || count($message['to']) === 0 )
//            throw new FlashyException("Message [to] is empty or invalid");

        return $this->flashy->client->post("messages/email");
    }

    /**
     * @param array $message
     * @return Response
     * @throws FlashyClientException|FlashyResponseException|FlashyAuthenticationException|FlashyException
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
        if( count($message) === 0 || gettype($message) != "array" )
            throw new FlashyException("Message body is invalid");
    }

}
