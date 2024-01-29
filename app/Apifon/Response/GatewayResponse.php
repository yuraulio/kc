<?php

namespace Apifon\Response {
    require_once 'ResultDetail.php';
    require_once 'ResultInfo.php';

    class GatewayResponse
    {
        public $request_id;
        public $reference;
        public $results = [];
        public $result_info;

        public function __construct($arrayValues)
        {
            $this->request_id = $arrayValues['request_id'];
            $this->reference = $arrayValues['reference'];
            $this->result_info = new ResultInfo($arrayValues['result_info']);
            $tmp = $arrayValues['results'];
            foreach ($tmp as $num => $result) {
                $temp = [];
                foreach ($result as $key => $value) {
                    $resDetail = new ResultDetail($value);
                    $temp[] = $resDetail;
                }
                $this->results[$num] = $temp;
            }
        }
    }
}
