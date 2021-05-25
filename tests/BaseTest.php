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
        require_once(__DIR__ . "/../src/Flashy/Flashy.php");

        $this->api = new Flashy(array(
            "api_key" => "tdbydujluskmwioteyfcw7zqjhwrfxss"
        ));
    }

    /**
     * @param $expected
     * @param $result
     */
    public function assertArrayContains($expected, $result)
    {
        $this->assertEquals($expected, Arr::only($result, array_keys($expected)));
    }

}
