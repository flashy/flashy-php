<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Helper;

class MessagesTest extends BaseTest
{

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyException
     * @throws FlashyResponseException
     */
    public function send_email_message_fail()
    {
        $this->init();

        $email = $this->api->messages->email([
            "from" => [],
        ]);

        $this->assertFalse($email->success());
    }

}
