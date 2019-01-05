<?php

namespace app\common\config;


class ResCode {

    public static $res_code = [
        self::BAD_REQUEST => 'bad_request',
        self::UNAUTHORIZED => 'unauthorized',
        self::FORBIDDEN => 'forbidden',
        self::INTERNAL_SEVER_ERROR => 'internal_sever_error',
        self::URL_NOT_EXIST => '链接不存在',
        self::REQUEST_FAIL => '请求失败',
        self::REQUEST_SUCCESS => '请求成功',
        self::NICKNAME_OR_PASSWORD_ERROR => '用户名或密码错误',
    ];

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const INTERNAL_SEVER_ERROR = 500;

    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 0;

    const NICKNAME_OR_PASSWORD_ERROR = 100;

}