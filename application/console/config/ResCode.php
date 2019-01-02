<?php

namespace app\console\config;


class ResCode {

    public static $res_code = [
        self::INTERVAL_EXCEPTION => '内部错误',
        self::AUTH_FAIL => '认证失败',
        self::REQUEST_PARAMS_ERROR => '请求参数有误',
        self::URL_NOT_EXIST => '链接不存在',
        self::REQUEST_FAIL => '请求失败',
        self::REQUEST_SUCCESS => '请求成功',
        self::VALID_FAIL => '验证失败',
    ];

    const INTERVAL_EXCEPTION = 500;
    const AUTH_FAIL = 403;
    const REQUEST_PARAMS_ERROR = 400;
    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 0;
    const VALID_FAIL = 100;

}