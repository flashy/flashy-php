<?php

namespace Flashy\Tests;

use Exception;
use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Helper;
use Illuminate\Support\Str;

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

        $this->assertTrue($lists->success());
    }

}
