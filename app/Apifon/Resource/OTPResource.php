<?php

namespace Apifon\Resource {
    use Apifon\Model\OtpRequest;
    use Apifon\Response\OtpResponse;
    use Apifon\Response\OtpVerifyResponse;

    class OTPResource extends AbstractResource
    {
        public function __construct()
        {
            parent::__construct($endpoint = '/services/otp');
        }

        /**
         * Sends an OTP create request.
         * @param $request
         * @return OtpResponse
         */
        public function create($request)
        {
            $this->setCurMethod('POST');
            $this->setCurEndpoint($this->getEndpoint() . '/create');
            if (is_string($request)) {
                $this->setCurBody($request);
            } elseif ($request instanceof OtpRequest) {
                $this->setCurBody($request->getCreateBody());
            }
            $return = json_decode(parent::dispatch(), true);
            $result = new OtpResponse($return);

            return $result;
        }

        /**
         * Sends an OTP Verification Request.
         * @param $reference
         * @param $otp
         * @return OtpVerifyResponse
         */
        public function verify($reference, $otp)
        {
            $this->setCurMethod('POST');
            $this->setCurEndpoint($this->getEndpoint() . '/verify/' . $reference . '/' . $otp);
            $this->setCurBody('');
            $return = json_decode(parent::dispatch(), true);
            $result = new OtpVerifyResponse($return);

            return $result;
        }
    }
}
