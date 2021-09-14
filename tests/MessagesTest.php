<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyAuthenticationException;
use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;

class MessagesTest extends BaseTest
{

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyException
     * @throws FlashyResponseException|FlashyAuthenticationException
     */
    public function send_email_message_fail()
    {
        $this->init();

        $email = $this->api->messages->email([
            "from" => [],
        ]);

        $this->assertFalse($email->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyException
     * @throws FlashyResponseException|FlashyAuthenticationException
     */
    public function send_single_sms()
    {
        $this->init();

        $email = $this->api->messages->sms([
            'to' => '972526845430',
            'from' => 'Flashyapp',
            'message' => 'Hello World!'
        ]);

        $this->assertTrue($email->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyException
     * @throws FlashyResponseException|FlashyAuthenticationException
     */
    public function send_single_email()
    {
        $this->init();

        $email = $this->api->messages->email([
            'template' => 'feppax990',
            'subject' => 'Anything',
            'from' => [
                'name' => 'Pixelshop',
                'email' => 'hello@pixelshop.io'
            ],
            'to' => [
                'name' => 'Snow',
                'email' => 'snow@business.com'
            ],
            'vars' => [
                'unique_hash' =>'somethinghere'
            ]
        ]);

        $this->assertTrue($email->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyException
     * @throws FlashyResponseException|FlashyAuthenticationException
     */
    public function send_bulk_email()
    {
        $this->init();

        $email = $this->api->messages->email([
            'template' => 'feppax990',
            'subject' => 'Anything',
            'from' => [
                'name' => 'Pixelshop',
                'email' => 'hello@pixelshop.io'
            ],
            'to' => [
                [
                    'name' => 'Snow',
                    'email' => 'snow@business.com'
                ],
                [
                    'name' => 'Henrich',
                    'email' => 'hen@business.com'
                ]
            ],
            'vars' => [
                'unique_hash' =>'somethinghere'
            ]
        ]);

        $this->assertTrue($email->success());
    }

}
