<?php

namespace Apifon\Security {
    class Hmac
    {
        public static function sign($message, $pkey)
        {
            return base64_encode(hash_hmac('SHA256', $message, $pkey, true));
        }
    }
}
