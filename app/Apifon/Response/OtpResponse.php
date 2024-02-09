<?php

namespace Apifon\Response {
    require_once 'ResultDetail.php';
    require_once 'ResultInfo.php';

    class OtpResponse
    {
        public $request_id;
        public $reference_id;
        public $result = [];
        public $result_info;

        public function __construct($arrayValues)
        {
            $this->request_id = $arrayValues['request_id'];
            if (isset($arrayValues['reference'])) {
                $this->reference_id = $arrayValues['reference'];
            }
            if (isset($arrayValues['reference_id'])) {
                $this->reference_id = $arrayValues['reference_id'];
            }
            $this->result_info = new ResultInfo($arrayValues['result_info']);
            if (isset($arrayValues['result'])) {
                $tmp = $arrayValues['result'];
                foreach ($tmp as $num => $result) {
                    $resDetail = new ResultDetail($result);
                    $this->result[] = $resDetail;
                }
            }
        }
    }
}
