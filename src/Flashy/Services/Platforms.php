<?php

namespace Flashy\Services;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy;
use Flashy\Response;

class Platforms {

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
     * @param array $platform
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function connect(array $platform)
    {
        return $this->flashy->client->post("platforms/connect", $platform);
    }

}
