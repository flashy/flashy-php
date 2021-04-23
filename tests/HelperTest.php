<?php

namespace Flashy\Tests;

use Flashy\Helper;

class HelperTest extends BaseTest
{

    /**
     * @test
     */
    public function get_helper()
    {
        $this->assertEquals(null, Helper::get("empty", []));
    }

}
