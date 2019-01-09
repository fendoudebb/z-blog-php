<?php

namespace app\index\controller;

use app\common\util\IdUtil;
use think\Log;

class Index {
    public function index() {
        $tag = input('get.tag');
        if (empty($tag)) {
            $tag = time();
        }
        for ($i = 0; $i < 30; $i++) {
            Log::log(IdUtil::generateId($tag));
            usleep(1000);
        }
        return 'ok';
    }
}
