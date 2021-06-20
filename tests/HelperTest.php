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

    /**
     * @test
     * @throws FlashyException
     */
    public function test_contains()
    {
        $this->init();

        $this->assertTrue(Helper::contains("Something", "Some"));

        $this->assertFalse(Helper::contains("Something", "Somesp"));

        $this->assertFalse(Helper::contains("Something", ["123", "456"]));

        $this->assertTrue(Helper::contains("Something", ["Some", "456"]));

        $this->assertTrue(Helper::contains("Something", ["Some", "thing"]));
    }

}
