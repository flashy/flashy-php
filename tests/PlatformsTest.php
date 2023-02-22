<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyAuthenticationException;
use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;

class PlatformsTest extends BaseTest
{

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     * @throws FlashyAuthenticationException
     */
    public function get_account_info()
    {
        $this->init();

        $account = $this->api->account->get();

        $this->assertTrue($account->success());
    }

}
