<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;

class ListsTest extends BaseTest
{

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function get_lists()
    {
        $this->init();

        $lists = $this->api->lists->get();

//        Helper::dd( $lists );

        $this->assertTrue($lists->success());
    }

}
