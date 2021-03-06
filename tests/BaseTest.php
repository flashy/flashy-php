<?php

namespace Flashy\Tests;

use Flashy\Exceptions\FlashyException;
use Flashy\Flashy;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{

    /**
     * @var Flashy
     */
    protected $api;

    /**
     * Init API
     * @throws FlashyException
     */
    public function init()
    {
        if( !class_exists("Flashy/Flashy") )
        {
            require_once(__DIR__ . "/../src/Flashy/Flashy.php");
        }

        // Account id = 2
        $this->api = new Flashy(array(
            "api_key" => "f3S5xILj9w83fa1PQFrICIyGljHecSKH"
        ));

        $this->api->client->setBasePath('https://storm.cbox/');
    }

    /**
     * @param $expected
     * @param $result
     */
    public function assertArrayContains($expected, $result)
    {
        $this->assertEquals($expected, Arr::only($result, array_keys($expected)));
    }

    /**
     * @test
     */
    public function firstTest()
    {
        $this->assertTrue(true);
    }

}
