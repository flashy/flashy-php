<?php

namespace Flashy\Flashy\Services;

use Flashy\Flashy\Exceptions\FlashyClientException;
use Flashy\Flashy\Exceptions\FlashyException;
use Flashy\Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy\Flashy;
use Flashy\Flashy\Helper;
use Flashy\Flashy\Response;

class Contacts {

    /**
     * @var Flashy
     */
    protected $flashy;

    public function __construct($flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @param $contact
     * @param string $primary_key
     * @param bool $tracking
     * @param null $overwrite
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function create($contact, $primary_key = "email", $tracking = true, $overwrite = null)
    {
        $payload = array(
            "primary_key" => $primary_key,
            "contact" => $contact
        );

        if( Helper::getAttribution() )
        {
            $payload["contact"]["attribution"] = Helper::getAttribution();
        }

        if( $overwrite )
        {
            $overwrite = "?overwrite=true";
        }

        $request = $this->flashy->client->post("contact" . $overwrite, $payload);

        if( $request->hasErrors() )
            return $request;

        if( $tracking === true )
        {
            $contact = $request->getData();

            $this->flashy->events->bulk($contact['contact_id']);

            Helper::setContact($contact);
        }

        return $request;
    }

    /**
     * @param $contact
     * @param string $primary_key
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException|FlashyException
     */
    public function update($contact, $primary_key = "email")
    {
        if( !isset($contact[$primary_key]) )
            throw new FlashyException("Contact primary key is missing from contact array");

        $payload = array(
            "primary_key" => $primary_key,
            "contact" => $contact
        );

        return $this->flashy->client->put('contact/' . $contact[$primary_key], $payload);
    }

    /**
     * @param $identifier
     * @param string $primary_key
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function get($identifier, $primary_key = "email")
    {
        return $this->flashy->client->get("contact/" . $identifier . "?primary_key=" . $primary_key);
    }

    /**
     * @param $identifier
     * @param string $primary_key
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function delete($identifier, $primary_key = "email")
    {
        return $this->flashy->client->delete("contact/" . $identifier . "?primary_key=" . $primary_key);
    }

    /**
     * @param $contact
     * @param $lists
     * @param string $primary_key
     * @param bool $tracking
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function subscribe($contact, $lists, $primary_key = "email", $tracking = true)
    {
        if( gettype($lists) !== "array" )
        {
            $lists = [$lists];
        }

        foreach( $lists as $list )
        {
            $contact["lists"][$list] = 1;
        }

        Helper::log($contact);

        return $this->create($contact, $primary_key, $tracking, true);
    }

    /**
     * @param $contact
     * @param $lists
     * @param string $primary_key
     * @param bool $tracking
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function unsubscribe($contact, $lists, $primary_key = "email", $tracking = true)
    {
        if( gettype($lists) !== "array" )
        {
            $lists = [$lists];
        }

        foreach( $lists as $list )
        {
            $contact["lists"][$list] = 0;
        }

        Helper::log($contact);

        return $this->create($contact, $primary_key, $tracking, true);
    }

    /**
     * @param $identifier
     * @param string $primary_key
     * @return Response
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function lists($identifier, $primary_key = "email")
    {
        return $this->flashy->client->get("contact/" . $identifier . "/lists?primary_key=" . $primary_key);
    }

}
