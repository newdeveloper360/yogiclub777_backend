<?php

namespace sonicpe\paymentV2\Exception;

use Exception;

class sonicpeSdkException extends Exception
{
    private $options;

    public function __construct($message, $object, $options, $code = 0, Exception $previous = null) {
        $this->options = $options;

        $this->debug_dump($message, $object);
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    function debug_dump(&$message, &$object) {
        if (isset($this->options['debug']) && $this->options['debug']) {
            $message = $message . ", Debug Info: " . json_encode($object);
        }
    }
}