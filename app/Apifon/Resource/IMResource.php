<?php

namespace Apifon\Resource {
    use Apifon\Model\IMChannel;
    use Apifon\Model\ImRequest;
    use Apifon\Model\RemoveRequest;
    use Apifon\Model\SubscribersViewRequest;
    use Apifon\Response\GatewayResponse;

    class IMResource extends AbstractResource
    {
        public function __construct()
        {
            parent::__construct($endpoint = '/services/im');
        }

        /**
         * Sends a Send Request.
         * @param $request
         * @return GatewayResponse
         */
        public function send($request)
        {
            $this->setCurMethod('POST');
            $this->setCurEndpoint($this->getEndpoint() . '/send');
            if (is_string($request)) {
                $this->setCurBody($request);
            } elseif ($request instanceof ImRequest) {
                $this->setCurBody($request->getBody());
            }

            $return = json_decode(parent::dispatch(), true);
            $result = new GatewayResponse($return);

            return $result;
        }

        /**
         * Sends a Rsemove Request.
         * @param $request
         * @return mixed
         */
        public function remove($request)
        {
            $this->setCurMethod('POST');
            $this->setCurEndpoint($this->getEndpoint() . '/remove');
            if (is_string($request)) {
                $this->setCurBody($request);
            } elseif ($request instanceof RemoveRequest) {
                $this->setCurBody($request->getBody());
            }

            $return = json_decode(parent::dispatch(), true);

            return $return;
        }
    }
}
