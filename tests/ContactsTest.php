<?php

namespace Flashy\Tests;

use Exception;
use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Helper;
use Illuminate\Support\Str;

class ContactsTest extends BaseTest
{
    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function create_contact_with_tracking()
    {
        $this->init();

        $email = Str::random(4) . "@flashyapp.com";

        $subscribe = $this->api->contacts->create(array(
            "email" => $email,
            "first_name" => "rafael",
            "custom" => "anything",
            'unknown' => true,
        ));

        $this->assertArrayContains([
            'email' => strtolower($email),
            'first_name' => 'rafael',
            'custom' => 'anything',
        ], $subscribe->getData());

        $this->assertEquals(base64_encode(strtolower($email)), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function create_contact_with_tracking_without_overwrite()
    {
        $this->init();

        $subscribe = $this->api->contacts->create(array(
            "email" => "flashy@gmail.com",
            "first_name" => "rafael"
        ));

        $this->assertArrayContains([
            'contact_id' => '90fa120ee8d74fbf8908802b1993e356',
            'email' => 'flashy@gmail.com',
            'first_name' => 'rafael',
        ], $subscribe->getData());

        $this->assertEquals(base64_encode("flashy@gmail.com"), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException
     */
    public function create_contact_with_tracking_overwrite()
    {
        $this->init();

        $subscribe = $this->api->contacts->create(array(
            "email" => "flashy@gmail.com",
            "last_name" => "mor"
        ), "email", true, true);

        $this->assertArrayContains([
            'contact_id' => '90fa120ee8d74fbf8908802b1993e356',
            'email' => 'flashy@gmail.com',
            'last_name' => 'mor',
        ], $subscribe->getData());

        $this->assertEquals(base64_encode("flashy@gmail.com"), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws Exception
     */
    public function create_contact_with_tracking_overwrite_by_contact_id()
    {
        $this->init();

        $custom = Str::random(5);

        $subscribe = $this->api->contacts->create(array(
            'contact_id' => '90fa120ee8d74fbf8908802b1993e356',
            "custom" => $custom
        ), "contact_id", true, true);

        $this->assertArrayContains([
            'contact_id' => '90fa120ee8d74fbf8908802b1993e356',
            "custom" => $custom
        ], $subscribe->getData());

        $this->assertEquals(base64_encode("flashy@gmail.com"), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function update_contact_by_email()
    {
        $this->init();

        $subscribe = $this->api->contacts->update(array(
            "email" => "flashy@gmail.com",
            "phone" => "0526845439"
        ));

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'email' => 'flashy@gmail.com',
            'phone' => '972526845439',
            'phone_country' => 'IL',
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function update_contact_by_contact_id()
    {
        $this->init();

        $custom = Str::random(5);

        $subscribe = $this->api->contacts->update(array(
            "contact_id" => "90fa120ee8d74fbf8908802b1993e356",
            "custom" => $custom
        ), "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'email' => 'flashy@gmail.com',
            "custom" => $custom
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function delete_contact()
    {
        $this->init();

        $contact = $this->api->contacts->create(array(
            "email" => "delete@gmail.com",
            "first_name" => "rafael"
        ));

        $delete = $this->api->contacts->delete("delete@flashyapp.com");

        $this->assertTrue($delete->success());

        $get = $this->api->contacts->get("delete@flashyapp.com");;

        $this->assertFalse($get->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function delete_contact_by_contact_id()
    {
        $this->init();

        $contact = $this->api->contacts->create(array(
            "email" => "delete@gmail.com",
            "first_name" => "rafael"
        ));

        $this->api->contacts->delete($contact['contact_id'], "contact_id");

        $get = $this->api->contacts->get("delete@flashyapp.com");

        $this->assertFalse($get->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function get_contact()
    {
        $this->init();

        $subscribe = $this->api->contacts->get("sam@flashyapp.com");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'email' => 'sam@flashyapp.com',
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function get_contact_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->get("d3c49d31a67f77ef3cbf0a83162f3f99", "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'email' => 'sam@flashyapp.com',
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function get_contact_not_found()
    {
        $this->init();

        $subscribe = $this->api->contacts->get("bad_contact");

        $this->assertFalse($subscribe->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function get_contact_not_found_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->get("bad_contact", "contact_id");

        $this->assertFalse($subscribe->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function subscribe_contact_to_a_list()
    {
        $this->init();

        $this->api->contacts->delete("sam@flashyapp.com");

        $subscribe = $this->api->contacts->subscribe("sam@flashyapp.com", [4, 6]);

        $this->assertTrue( $subscribe->success() );

        $this->assertArrayContains([
            'lists' => [4 => true, 6 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function subscribe_contact_to_a_list_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->subscribe("d3c49d31a67f77ef3cbf0a83162f3f99", 5, "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => true, 6 => true, 5 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function unsubscribe_contact_to_a_list()
    {
        $this->init();

        $subscribe = $this->api->contacts->unsubscribe("sam@flashyapp.com", [4, 6]);

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => false, 6 => false, 5 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function unsubscribe_contact_to_a_list_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->unsubscribe("d3c49d31a67f77ef3cbf0a83162f3f99", 5, "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => false, 6 => false, 5 => false],
        ], $subscribe->getData());
    }

}
