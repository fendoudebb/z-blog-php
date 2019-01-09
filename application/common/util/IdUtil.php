<?php

namespace app\common\util;


class IdUtil {

    public static function generateId($tag) {
        return md5(uniqid(microtime(true) . $tag));
    }

}