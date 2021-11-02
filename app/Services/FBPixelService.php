<?php 

namespace App\Services;

//require __DIR__ . '/vendor/autoload.php';

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use Auth;

class FBPixelService
{

    public $accessToken;
    public $pixelID;

    public function __construct(){

        //knowcrunch
        $this->accessToken = 'EAA3qLyUpL7kBAI4e5zZBqa2g3jxyNIZBLofS6Nk85UG5mMs9QuVHpokkgYHzxfZBNm0t1Ty2uAbpxqBSyj5yrhBGInDr9DW6u3RRbijMSw6XN27NoEMzcOnskWZAVjQ8quN53YX1XhFZAa1aanQ3BZAgRUiT4ZAAtdU3GJOpjWhIjRdzWsVyqZCP';//env('ACCESS_TOKEN');
        $this->pixelID = '1745172385734431';//env('PIXEL_ID');

        //$this->accessToken = 'EAAM3tzaF3aEBAPuliANvgmVV0Gacw7ziWDwANMD1l0LcQoerZCxCZCkqY2ZAKsix1vVTAtZBD4Ev15RcezaJNJqHTxhF7rKgHQoWfuXYEMe3kMDVX3znt3bT2P8zlUwiSe93HwtaljiHBcDzjwXeWenqCzH2Iqnuy4WCVlqlpaZABO4ZByN346';
        //$this->pixelID = '811415882786573';


        $api = Api::init(null, null, $this->accessToken);
        $api->setLogger(new CurlLogger());

    }

    public function sendEvent($event,$data){

        $user_data = (new UserData())
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT']);
   
        //dd($data);
        $content = (new Content())
            ->setProductId($data['Product_id'])
            ->setQuantity($data['Quantity']);
            //->setItemPrice($data['price']);
            //->setDeliveryCategory($data['ProductCategory']);

        //dd($event);
        $custom_data = (new CustomData())
                ->setContents(array($content))
                ->setCurrency('EUR')
                ->setValue($data['price']);
        
        $event = (new Event())
                ->setEventName($event)
                ->setEventTime(time())
                ->setUserData($user_data)
                ->setCustomData($custom_data);
        
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

      

        $response = $request->execute();

        //dd($response);

    }

    private function getUserData(){

        $remoteAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "127.0.0.1";
        $httpUSER = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36";

        if($user = Auth::user()){

            $user_data = (new UserData())
                ->setEmail($user->email)
                ->setPhone($user->mobile)
                ->setFirstName($user->firstname)
                ->setLastName($user->lastname)
                //->setCountry($user->country)
                ->setCity($user->city)
                ->setClientIpAddress($remoteAddress)
                ->setClientUserAgent($httpUSER);

        }else{
            $user_data = (new UserData())
                ->setClientIpAddress($remoteAddress)
                ->setClientUserAgent($httpUSER);
        }
        return $user_data;
    }

    public function sendLeaderEvent($data){

        $eventData = ['event_id' => $data['Event_ID'],'event_name'=>'Lead','event_source_url'=>url('/'),'action_source'=>'website'];
        //$eventData = ['event_id' => $data['Event_ID'],'event_source_url'=>url('/'),'action_source'=>'website'];
   

        $event = (new Event($eventData))
                //->setEventName('Lead')
                ->setEventTime(time())
                ->setUserData($this->getUserData());
        
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);
      

        $response = $request->execute();

        //dd($response);

    }

    public function sendViewContentEvent($data){
 
        $eventData = ['event_id' => $data['tigran']['Event_ID'],'event_name'=>'View Content','event_source_url'=>url('/'),
                        'action_source'=>'website'
                    ];

        $customData = ['content_ids' => [$data['tigran']['Product_id']], 
                    'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'],'currency' => 'EUR',
                    'value' => $data['tigran']['price']
        ];
        
         $custom_data = new CustomData($customData);

         $event = (new Event($eventData))
                 //->setEventName('AddtoCart')
                 ->setEventTime(time())
                 ->setUserData($this->getUserData())
                 ->setCustomData($custom_data);
    
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

      

        $response = $request->execute();

        //dd($response);

    }

    public function sendAddToCart($data){

        $eventData = ['event_id' => $data['tigran']['Event_ID'].'p','event_name'=>'AddToCart','event_source_url'=>url('/'),
                        'action_source'=>'website'
                    ];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']], 
        'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'],'currency' => 'EUR',
        'value' => $data['tigran']['price']
        ];




        //dd($customData);
        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                //->setEventName('AddtoCart')
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);
        
        
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
       /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);
      
        $response = $request->execute();

    }

    public function sendPurchaseEvent($data){
 
        if(!isset($data['tigran'])){
            return;
        }

        $eventData = ['event_id' => $data['tigran']['Event_ID'],'event_name'=>'Purchase Event','event_source_url'=>url('/'),
                        'action_source'=>'website'
                    ];
        
                    $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']], 
                    'currency' => 'EUR', 'value' => $data['tigran']['price']
                    ];
                    
                    $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                //->setEventName('AddtoCart')
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);
        
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

            $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

      

        $response = $request->execute();

        //dd($response);

    }

    public function sendPageViewEvent(){
 
        $eventData = ['event_id' => 'kc_' . time(),'event_name'=>'PageView','event_source_url'=>url('/'),'action_source'=>'website'];
        
        $event = (new Event($eventData))
                //->setEventName('PageView')
                ->setEventTime(time())
                ->setUserData($this->getUserData());
        
        $events = array();
        array_push($events, $event);
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

        $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

      

        $response = $request->execute();

        //dd($response);

    }

    public function sendAddPaymentInfoEvent($data){
 
        $eventData = ['event_id' => $data['tigran']['Event_ID'],'event_name'=>'Add Payment Info','event_source_url'=>url('/'),
            'action_source'=>'website'
        ];

        $customData = ['content_type' => 'product', 'content_ids' => [$data['tigran']['Product_id']], 
        'content_name' => $data['tigran']['ProductName'], 'content_category' => $data['tigran']['ProductCategory'],'currency' => 'EUR',
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                //->setEventName('AddtoCart')
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);
        
        $events = array();
        array_push($events, $event);
        
        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

            $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();

        //dd($response);

    }

    public function sendCompleteRegistrationEvent($data){
        
        $eventData = ['event_id' => $data['tigran']['Event_ID'],'event_name'=>'CompleteRegistration','event_source_url'=>url('/'),
            'action_source'=>'website'
        ];

        $customData = ['content_name' => $data['tigran']['ProductName'], 'currency' => 'EUR','status'=>true,'value'=>$data['tigran']['price']
        ];

        $custom_data = new CustomData($customData);

        $event = (new Event($eventData))
                //->setEventName('AddtoCart')
                ->setEventTime(time())
                ->setUserData($this->getUserData())
                ->setCustomData($custom_data);

        $events = array();
        array_push($events, $event);

        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

            $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendContactEvent(){
        
        $eventData = ['event_id' => 'kc_' . time(),'event_name'=>'Contact','event_source_url'=>url('/'),'action_source'=>'website'];

        $event = (new Event($eventData))
                //->setEventName('Add Payment Info')
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = array();
        array_push($events, $event);

        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

            $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

    public function sendStartTrialEvent(){
        
        $eventData = ['event_id' => 'kc_' . time(),'event_name'=>'Start Trial','event_source_url'=>url('/'),'action_source'=>'website'];

        $event = (new Event($eventData))
                //->setEventName('Add Payment Info')
                ->setEventTime(time())
                ->setUserData($this->getUserData());

        $events = array();
        array_push($events, $event);

        //TEST63100 knowcrunch
        //TEST9833
        /*$request = (new EventRequest($this->pixelID,['test_event_code'=>'TEST63100']))
            ->setEvents($events);*/

            $request = (new EventRequest($this->pixelID))
            ->setEvents($events);

        $response = $request->execute();
    }

}