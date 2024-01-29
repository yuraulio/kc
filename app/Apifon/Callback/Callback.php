<?php

namespace Apifon\Callback {
        class Callback
        {
            public $url;
            public $request_id;
            public $reference_id;
            public $data = [];
            public $account_id;
            public $type;

            public function __construct($response)
            {
                $data = json_decode($response);

                if (isset($data->url)) {
                    $this->setUrl($data->url);
                }

                if (isset($data->request_id)) {
                    $this->setRequestId($data->request_id);
                }

                if (isset($data->reference_id)) {
                    $this->setReferenceId($data->reference_id);
                }

                if (isset($data->account_id)) {
                    $this->setAccountId($data->account_id);
                }

                if (isset($data->type)) {
                    $this->setType($data->type);
                }

                $counter = 0;
                foreach ($data->data as $val) {
                    if (isset($val->from)) {
                        $this->data[$counter]['from'] = $val->from;
                    }

                    if (isset($val->to)) {
                        $this->data[$counter]['to'] = $val->to;
                    }

                    if (isset($val->message_id)) {
                        $this->data[$counter]['message_id'] = $val->message_id;
                    }

                    if (isset($val->status->code)) {
                        $this->data[$counter]['status']['code'] = $val->status->code;
                    }

                    if (isset($val->status->text)) {
                        $this->data[$counter]['status']['text'] = $val->status->text;
                    }

                    if (isset($val->error_code)) {
                        $this->data[$counter]['error_code'] = $val->error_code;
                    }

                    if (isset($val->timestamp)) {
                        $this->data[$counter]['timestamp'] = $val->timestamp;
                    }

                    if (isset($val->mccmnc)) {
                        $this->data[$counter]['mccmnc'] = $val->mccmnc;
                    }
                }

                return $this;
            }

            /**
             * @return mixed
             */
            public function getUrl()
            {
                return $this->url;
            }

            /**
             * @param mixed $url
             * @return Callback
             */
            public function setUrl($url)
            {
                $this->url = $url;

                return $this;
            }

            /**
             * @return mixed
             */
            public function getRequestId()
            {
                return $this->request_id;
            }

            /**
             * @param mixed $request_id
             * @return Callback
             */
            public function setRequestId($request_id)
            {
                $this->request_id = $request_id;

                return $this;
            }

            /**
             * @return mixed
             */
            public function getReferenceId()
            {
                return $this->reference_id;
            }

            /**
             * @param mixed $reference_id
             * @return Callback
             */
            public function setReferenceId($reference_id)
            {
                $this->reference_id = $reference_id;

                return $this;
            }

            /**
             * @return array
             */
            public function getData()
            {
                return $this->data;
            }

            /**
             * @param array $data
             * @return Callback
             */
            public function setData($data)
            {
                $this->data = $data;

                return $this;
            }

            /**
             * @return mixed
             */
            public function getAccountId()
            {
                return $this->account_id;
            }

            /**
             * @param mixed $account_id
             * @return Callback
             */
            public function setAccountId($account_id)
            {
                $this->account_id = $account_id;

                return $this;
            }

            /**
             * @return mixed
             */
            public function getType()
            {
                return $this->type;
            }

            /**
             * @param mixed $type
             * @return Callback
             */
            public function setType($type)
            {
                $this->type = $type;

                return $this;
            }
        }
    }
