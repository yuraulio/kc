<?php

namespace Apifon\Response {
    class BalanceResponse
    {
        public $balance;
        public $plafon;

        public function __construct($arrayValues)
        {
            $this->balance = $arrayValues['balance'];
            $this->plafon = $arrayValues['plafon'];
        }
    }
}
