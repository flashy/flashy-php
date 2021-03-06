<?php

namespace Flashy\Tests;

use Exception;
use Flashy\Exceptions\FlashyAuthenticationException;
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
     * @throws FlashyException|FlashyAuthenticationException
     */
    public function create_contact_with_tracking()
    {
        $this->init();

        $email = Str::random(4) . "@flashyapp.com";

        $subscribe = $this->api->contacts->create(array(
            "email" => $email,
            "first_name" => "rafael",
            "custom" => "anything", // Must be created on the account to be saved
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
     */
    public function create_contact_with_tracking_without_overwrite()
    {
        $this->init();

        $subscribe = $this->api->contacts->create(array(
            "email" => "flashy@gmail.com",
            "first_name" => "rafael"
        ));

        $this->assertArrayContains([
            'contact_id' => 'f81fac9cb902e0ea1ad67ceaa60c4c5f',
            'email' => 'flashy@gmail.com',
            'first_name' => 'rafael',
        ], $subscribe->getData());

        $this->assertEquals(base64_encode("flashy@gmail.com"), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException|FlashyAuthenticationException
     */
    public function create_contact_with_tracking_overwrite()
    {
        $this->init();

        $subscribe = $this->api->contacts->create(array(
            "email" => "flashy@gmail.com",
            "last_name" => "mor"
        ), "email", true, true);

        $this->assertArrayContains([
            'contact_id' => 'f81fac9cb902e0ea1ad67ceaa60c4c5f',
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
            'contact_id' => 'f81fac9cb902e0ea1ad67ceaa60c4c5f',
            "custom" => $custom
        ), "contact_id", true, true);

        $this->assertArrayContains([
            'contact_id' => 'f81fac9cb902e0ea1ad67ceaa60c4c5f',
            "custom" => $custom
        ], $subscribe->getData());

        $this->assertEquals(base64_encode("flashy@gmail.com"), Helper::getFakeCookie("flashy_id"));
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
     */
    public function update_contact_by_contact_id()
    {
        $this->init();

        $custom = Str::random(5);

        $subscribe = $this->api->contacts->update(array(
            "contact_id" => "f81fac9cb902e0ea1ad67ceaa60c4c5f",
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
     */
    public function get_contact_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->get("f81fac9cb902e0ea1ad67ceaa60c4c5f", "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'email' => 'flashy@gmail.com',
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
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
     * @throws FlashyAuthenticationException
     */
    public function subscribe_contact_to_a_list()
    {
        $this->init();

        $this->api->contacts->delete("sam@flashyapp.com");

        $subscribe = $this->api->contacts->subscribe(['email' => 'sam@flashyapp.com'], [4, 6]);

        $this->assertTrue( $subscribe->success() );

        $this->assertArrayContains([
            'lists' => [4 => true, 6 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     * @throws FlashyAuthenticationException
     */
    public function subscribe_contact_to_a_list_identifier()
    {
        $this->init();

        $this->api->contacts->delete("sam@flashyapp.com");

        $subscribe = $this->api->contacts->subscribe('sam@flashyapp.com', [4, 6]);

        $this->assertTrue( $subscribe->success() );

        $this->assertArrayContains([
            'lists' => [4 => true, 6 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     * @throws FlashyAuthenticationException
     */
    public function subscribe_contact_to_a_list_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->subscribe(['contact_id' => '24d82d6602bc5ebe52282f4456095fd7'], 5, 'contact_id');

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => true, 6 => true, 5 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     * @throws FlashyAuthenticationException
     */
    public function unsubscribe_contact_to_a_list()
    {
        $this->init();

        $subscribe = $this->api->contacts->unsubscribe(["email" => "sam@flashyapp.com"], [4, 6]);

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => false, 6 => false, 5 => true],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     * @throws FlashyAuthenticationException
     */
    public function unsubscribe_contact_to_a_list_by_identifier()
    {
        $this->init();

        $this->api->contacts->delete("sam@flashyapp.com");

        $this->api->contacts->subscribe('sam@flashyapp.com', [4, 6]);

        $unsubscribe = $this->api->contacts->unsubscribe("sam@flashyapp.com", 4);

        $this->assertTrue($unsubscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => false, 6 => true],
        ], $unsubscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
     */
    public function unsubscribe_contact_to_a_list_by_contact_id()
    {
        $this->init();

        $subscribe = $this->api->contacts->unsubscribe(["contact_id" => "24d82d6602bc5ebe52282f4456095fd7"], [5,6], "contact_id");

        $this->assertTrue($subscribe->success());

        $this->assertArrayContains([
            'lists' => [4 => false, 6 => false, 5 => false],
        ], $subscribe->getData());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException|FlashyAuthenticationException
     */
    public function get_contact_properties()
    {
        $this->init();

        $properties = $this->api->contacts->properties();

        $this->assertTrue($properties->success());
    }

    /**
     * @test
     * @throws FlashyClientException
     * @throws FlashyResponseException
     * @throws FlashyException|FlashyAuthenticationException
     */
    public function create_contacts_with_lists_and_block()
    {
        $this->init();

        $email = Str::random(4) . "@flashyapp.com";

        $subscribe = $this->api->contacts->create(array(
            "email" => $email,
            "first_name" => "rafael",
            "custom" => "anything",
            'unknown' => true,
            "lists" => [
                150 => true,
                155 => false
            ]
        ));

        $this->assertArrayContains(['lists' => [150 => true, 155 => false]], $subscribe->getData());

        $this->api->contacts->block($email);

        $contact = $this->api->contacts->get($email);

        $this->assertArrayContains([
            'lists' => [150 => false, 155 => false]
        ], $contact->getData());
    }

}
