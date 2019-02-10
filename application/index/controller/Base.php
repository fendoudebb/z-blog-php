<?php

namespace app\index\controller;

use think\Controller;
use think\Log;
use think\Request;

abstract class Base extends Controller {

    protected $ip;
    protected $url;
    protected $isMobile;

    public function _initialize() {
        $request = Request::instance();
        $this->ip = $request->ip();
        $this->url = $request->url();
        $this->isMobile = $request->isMobile();
    }

    public function log($msg) {
        Log::log("[$this->url]-[$this->ip]-> " . $msg);
    }

    public function logException($errorMsg) {
        Log::log("[$this->url]-[$this->ip]-> $errorMsg");
    }

}

