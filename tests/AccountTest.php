<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Helper;

class AccountTest extends BaseTest
{

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function get_account_info()
    {
        $this->init();

        $account = $this->api->account->get();

        $this->assertTrue($account->success());
    }

}
