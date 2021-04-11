<?php

namespace Flashy\Services;

use Flashy\Exceptions\FlashyClientException;
use Flashy\Exceptions\FlashyException;
use Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy;
use Flashy\Helper;

class Catalogs {

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
     * @return mixed
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
            return $request->getBody();

        $contact = $request->getData();

        if( $tracking === true )
        {
            $this->flashy->events->bulk($contact['contact_id']);

            Helper::setContact($contact);
        }

        return $contact;
    }

    /**
     * @param $contact
     * @param string $primary_key
     * @return array|bool|float|int|mixed
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

        $request = $this->flashy->client->put('contact/', $payload);

        return $request->getBody();
    }

    /**
     * @param $identifier
     * @param string $primary_key
     * @return array|bool|float|int|mixed
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function get($identifier, $primary_key = "email")
    {
        $request = $this->flashy->client->get("contact/" . $identifier . "?primary_key=" . $primary_key);

        return $request->getBody();
    }

    /**
     * @param $identifier
     * @param string $primary_key
     * @return array|bool|float|int|mixed
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function delete($identifier, $primary_key = "email")
    {
        $request = $this->flashy->client->delete("contact/" . $identifier . "?primary_key=" . $primary_key);

        return $request->getBody();
    }

}
