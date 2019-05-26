<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService{
    private $sid    = "AC8450b5db3a8484df19e37fe40b31257a";
    private $token  = "40a1e15fe4b9f63d1ee965b07c4a6c2a";
    
    private $twilio;
    public function __construct() {
        $this->twilio = new Client($this->sid, $this->token);
    }
    
    public function sendMessage($message, $to = "+15005550010", $from = "+15005550006"){
        $this->twilio->messages
            ->create($to, // to
                     array(
                         "body" => $message,
                         "from" => $from
                     )
            );
    }

    
}