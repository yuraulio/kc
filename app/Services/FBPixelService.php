<?php

namespace App\Services;

use Auth;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

class FBPixelService
{
    public $accessToken;
    public $pixelID;

    public function __construct()
    {
        return;
        //knowcrunch
        $this->accessToken = 'EAA3qLyUpL7kBAI4e5zZBqa2g3jxyNIZBLofS6Nk85UG5mMs9QuVHpokkgYHzxfZBNm0t1Ty2uAbpxqBSyj5yrhBGInDr9DW6u3RRbijMSw6XN27NoEMzcOnskWZAVjQ8quN53YX1XhFZAa1aanQ3BZAgRUiT4ZAAtdU3GJOpjWhIjRdzWsVyqZCP'; //env('ACCESS_TOKEN');
        $this->pixelID = '1745172385734431';

        $api = Api::init(null, null, $this->accessToken);
        $api->setLogger(new CurlLogger());
    }

    public function sendEvent($event, $data)
    {
        return;
        $user_data = (new UserData())
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT']);

        $content = (new Content())
            ->setProductId($data['Product_id'])
            ->setQuantity($data['Quantity']);

        $custom_data = (new CustomData())
                ->setContents([$content])
                ->setCurrency('EUR')
                ->setValue($data['Price']);

        $event = (new Event())
                ->setEventName($event)
                ->setEventTime(time())
                ->setUserData($user_data)
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    private function getUserData()
    {
        return;
        $remoteAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $httpUSER = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36';

        if ($user = Auth::user()) {
            $user_data = (new UserData())
                ->setEmail($user->email)
                ->setPhone($user->mobile)
                ->setFirstName($user->firstname)
                ->setLastName($user->lastname)
                ->setCity($user->city)
                ->setClientIpAddress($remoteAddress)
                ->setClientUserAgent($httpUSER);
        } else {
            $user_data = (new UserData())
                ->setClientIpAddress($remoteAddress)
                ->setClientUserAgent($httpUSER);
        }

        return $user_data;
    }

    public function sendLeaderEvent($data)
    {
        return;
        $eventData = ['event_id' => $data['Event_ID'], 'event_name'=>'Lead', 'event_source_url'=>url('/'), 'action_source'=>'website'];

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendViewContentEvent($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'] . 'v', 'event_name'=>'ViewContent', 'event_source_url'=>url('/'),
            'action_source'=>'website',
        ];

        $customData = ['content_ids' => [$data['tigran']['Product_id']],
            'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'], 'currency' => 'EUR',
            'value' => $data['tigran']['Price'],
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                 ->setEventTime(time())
                 ->setUserData($this->getUserData())
                 ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendAddToCart($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'] . 'p', 'event_name'=>'AddToCart', 'event_source_url'=>url('/'),
            'action_source'=>'website',
        ];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']],
            'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'], 'currency' => 'EUR',
            'value' => $data['tigran']['Price'],
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendPurchaseEvent($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        if ((int) $data['tigran']['Price'] <= 0) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'], 'event_name'=>'Purchase', 'event_source_url'=>url('/'),
            'action_source'=>'website'];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']],
            'currency' => 'EUR', 'value' => $data['tigran']['Price'],
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendPageViewEvent()
    {
        return;
        $eventData = ['event_id' => 'kc_' . time(), 'event_name'=>'PageView', 'event_source_url'=>url('/'), 'action_source'=>'website'];

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendAddBillingInfoEvent($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'], 'event_name'=>'Add Billing Info', 'event_source_url'=>url('/'),
            'action_source'=>'website',
        ];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']],
            'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'], 'currency' => 'EUR',
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendAddPaymentInfoEvent($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'], 'event_name'=>'AddPaymentInfo', 'event_source_url'=>url('/'),
            'action_source'=>'website',
        ];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']],
            'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'], 'currency' => 'EUR',
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendCompleteRegistrationEvent($data)
    {
        return;
        if (!isset($data['tigran'])) {
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'], 'event_name'=>'CompleteRegistration', 'event_source_url'=>url('/'),
            'action_source'=>'website',
        ];

        $customData = ['content_name' => $data['tigran']['ProductName'], 'currency' => 'EUR', 'status'=>true, 'value'=>$data['tigran']['Price'],
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendContactEvent()
    {
        return;
        $eventData = ['event_id' => 'kc_' . time(), 'event_name'=>'Contact', 'event_source_url'=>url('/'), 'action_source'=>'website'];

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendStartTrialEvent()
    {
        return;
        $eventData = ['event_id' => 'kc_' . time(), 'event_name'=>'Start Trial', 'event_source_url'=>url('/'), 'action_source'=>'website'];

        $event = (new Event($eventData))
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = [];
        array_push($events, $event);

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }
}
