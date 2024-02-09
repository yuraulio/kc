<?php

namespace Apifon {
    use Apifon\Resource\AbstractResource;
    use Apifon\Security\Hmac;

    class Mookee
    {
        private static $production_url = 'https://ars.apifon.com';
        private static $staging_url = 'http://ars.staging.apifon.com';
        private static $base_url;
        private static $credentials = [];
        private static $staging = false;
        private static $instance = null;
        private static $key = '';

        private function __construct()
        {
        }

        public static function getInstance()
        {
            // Check if instance already exists
            if (self::$instance == null) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function setStaging($enable = false)
        {
            self::$staging = $enable;
        }

        public static function dispatch(AbstractResource $resource)
        {
            if (self::$staging) {
                self::$base_url = self::$staging_url;
            } else {
                self::$base_url = self::$production_url;
            }

            $endpoint = $resource->getCurEndpoint();
            $body = $resource->getCurBody();
            $method = $resource->getCurMethod();

            try {
                return self::makeCall($endpoint, $method, $body);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        private static function makeCall($endpoint, $method, $body)
        {
            $url = self::$base_url . $endpoint;

            //Get the date from internal function
            $requestDate = self::getRequestDate();

            //Build the message

            $message = $method . "\n"
                . $endpoint . "\n"
                . $body . "\n"
                . $requestDate;

            //Get the signature from the hmac class
            $signature = Hmac::sign($message, self::$credentials[self::$key]['secret']);

            //Build the autorization header
            $header = [];
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: ApifonWS ' . self::$credentials[self::$key]['token'] . ':' . $signature;
            $header[] = 'X-ApifonWS-Date: ' . $requestDate;

            //Make the curl call
            //  dd($url);
            $curl = \curl_init($url);
            \curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            \curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            \curl_setopt($curl, CURLOPT_HEADER, false);
            \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            \curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            \curl_setopt($curl, CURLOPT_POST, true);
            \curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

            $response = \curl_exec($curl);

            $status = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 200 && !(is_int(strpos($url, '/verify/')) && $status === 403) && !(is_int(strpos($url, 'otp/create')) && $status === 403)) {
                throw new \Exception("Error: call to URL $url failed with status $status, response $response, curl_error " . \curl_error($curl) . ', curl_errno ' . \curl_errno($curl));
            }

            \curl_close($curl);

            return $response;
        }

        //Set credentials
        public static function addCredentials($key, $token, $privateKey)
        {
            self::$credentials[$key] = ['token'=>$token, 'secret'=>$privateKey];
        }

        public static function setActiveCredential($key)
        {
            self::$key = $key;
        }

        //private set date with timezone
        private static function getRequestDate()
        {
            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone('GMT'));

            return $dateTime->format('D, d M Y H:i:s T');
        }
    }
}
