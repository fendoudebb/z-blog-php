<?php

namespace app\common\exception;


class SystemException extends \RuntimeException {

    protected $error;

    public function __construct($error) {
        $this->error   = $error;
        $this->message = is_array($error) ? implode("\n\r", $error) : $error;
    }

    public function getError() {
        return $this->error;
    }

}