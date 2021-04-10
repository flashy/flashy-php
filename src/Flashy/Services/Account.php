<?php

namespace Flashy\Flashy\Services;

use Flashy\Flashy\Exceptions\FlashyClientException;
use Flashy\Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy\Flashy;
use Flashy\Flashy\Response;

class Account {

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
     */
    public function get()
    {
        return $this->flashy->client->get("account");
    }

}
