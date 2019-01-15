<?php

namespace app\index\controller;


class Example extends Base {

    public function jsControl() {
        return $this->fetch('js_control');
    }
}