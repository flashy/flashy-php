<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyException;
use Flashy\Flashy;
use Flashy\Helper;

class HelperTest extends BaseTest
{

    /**
     * @var Flashy
     */
    private $flashy;

    /**
     * @test
     * @throws FlashyException
     */
    public function get_helper()
    {
        $this->init();

        $this->assertEquals(null, Helper::get("empty", []));
    }

    /**
     * @test
     * @throws FlashyException
     */
    public function try_something_or_log()
    {
        $this->init();

        $list = 4;

        Helper::tryOrLog(function() use ($list) {

            Helper::dd($list);

            badfunction($as);

        });

        $this->assertTrue(true);
    }

}
