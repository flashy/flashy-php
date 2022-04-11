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

        Helper::tryOrLog(function() {

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

    /**
     * @test
     */
    public function get_root_domain()
    {
        $this->assertEquals("flashy.studio", Helper::getRootDomain("woo.flashy.studio"));

        $this->assertEquals("flashy.academy", Helper::getRootDomain("woo.flashy.academy"));

        $this->assertEquals("google.co.il", Helper::getRootDomain("demo.google.co.il"));

        $this->assertEquals("google.co.il", Helper::getRootDomain("google.co.il"));

        $this->assertEquals("google.academy", Helper::getRootDomain("google.academy"));

        $this->assertEquals("google.academy", Helper::getRootDomain("demo.google.academy"));

        $this->assertEquals("ניסיון.co.il", Helper::getRootDomain("ניסיון.co.il"));
    }

}
