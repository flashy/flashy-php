<?php

namespace Flashy\Flashy\Services;

use Flashy\Flashy\Exceptions\FlashyClientException;
use Flashy\Flashy\Exceptions\FlashyResponseException;
use Flashy\Flashy\Flashy;
use Flashy\Flashy\Helper;

class Events {

    /**
     * @var Flashy
     */
    protected $flashy;

    /**
     * Events constructor.
     * @param $flashy
     */
    public function __construct($flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @param $event
     * @param $params
     * @return array
     * @throws FlashyClientException
     * @throws FlashyResponseException
     */
    public function track($event, $params)
    {
        $contact_id = Helper::getCookie("fls_id");

        if( !isset($contact_id) && !isset($params['flashy_id']) && !isset($params['contact_id']) && !isset($params['email']) )
        {
            return array('success' => false, 'errors' => 'email / contact id / flashy id not found');
        }

        $payload = array(
            "event" => $event,
            "body" => $params
        );

        $track = $this->flashy->client->post("track", $payload);
    }

    public function bulk($contact_id = null, $events_list = "cookie", $identity = "contact_id")
    {
        if( $contact_id == null )
        {
            $identity = "flashy_id";

            $contact_id = $this->getContactId($contact_id);
        }

        if($contact_id == null) return array('success' => false, 'errors' => 'contact id or flashy id not found');

        if($events_list == "cookie" && isset($_COOKIE['flashy_thunder']))
        {
            $events = json_decode(base64_decode($_COOKIE['flashy_thunder']), true);

            foreach ($events as &$event)
            {
                $event['body'][$identity] = ( $identity == "contact_id" ) ? $contact_id : base64_encode($contact_id);
            }
        }
        else if( $events_list !== "cookie" )
        {
            $events = $events_list;
        }
        else
        {
            $events = array();
        }

        if(count($events) == 0) return array('success' => false, 'errors' => 'events not found');

        $_params = array("events" => $events);

        $call = $this->master->call('bulk', $_params, 'events');

        if( isset($call['success']) && $call['success'] == true )
        {
            $this->deleteCookie();
        }

        return $call;
    }
}
