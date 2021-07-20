<?php

namespace Apifon\Resource {


    use Apifon\Model\IRequest;
    use Apifon\Mookee;

    abstract class AbstractResource {

        private $endpoint;
        private $curEndpoint;
        private $curBody;
        private $curMethod;

        public function __construct($endpoint)
        {
            $this->setEndpoint($endpoint);
        }

        public function getEndpoint()
        {
            return $this->endpoint;
        }

        public function setEndpoint($endpoint)
        {
            $this->endpoint = $endpoint;
            return $this;
        }

        public function getCurEndpoint()
        {
            return $this->curEndpoint;
        }

        public function setCurEndpoint($curEndpoint)
        {
            $this->curEndpoint = $curEndpoint;
        }

        public function getCurBody()
        {
            return $this->curBody;
        }

        public function setCurBody($curBody)
        {
            $this->curBody = $curBody;
        }

        public function getCurMethod()
        {
            return $this->curMethod;
        }

        public function setCurMethod($curMethod)
        {
            $this->curMethod = $curMethod;
        }

        public function dispatch(){
            return Mookee::dispatch($this);
        }

        public function create($request){
            $this->curMethod = "POST";
            $this->curEndpoint = $this->endpoint . "/create";
            $this->curBody = $request->getCreateBody();

            return json_decode($this->dispatch(), true);
        }

        public function read($request){
            $this->curMethod = "GET";
            $this->curEndpoint = $this->endpoint . "/read";
            $this->curBody = $request->getReadBody();

            return json_decode($this->dispatch(), true);
        }

        public function update($request){
            $this->curMethod = "POST";
            $this->curEndpoint = $this->endpoint . "/update";
            $this->curBody = $request->getUpdateBody();

            return json_decode($this->dispatch(), true);
        }

        public function delete($request){
            $this->curMethod = "DELETE";
            $this->curEndpoint = $this->endpoint . "/delete";
            $this->curBody = $request->getDeleteBody();

            return json_decode($this->dispatch(), true);
        }
    }


}

?>