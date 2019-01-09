<?php

namespace app\common\config;


class ResCode {

    public static $res_code = [
        self::BAD_REQUEST => 'bad_request',
        self::UNAUTHORIZED => 'unauthorized',
        self::FORBIDDEN => 'forbidden',
        self::INTERNAL_SEVER_ERROR => 'internal_sever_error',
        self::URL_NOT_EXIST => 'url not exist',
        self::REQUEST_FAIL => 'request fail',
        self::REQUEST_SUCCESS => 'request success',
        self::USERNAME_OR_PASSWORD_ERROR => 'username or password error',
        self::USER_ROLE_INFO_ERROR => "user's role info error",
        self::TAG_IS_EMPTY => "tag is empty",
        self::TAG_TYPE_IS_EMPTY => "tag type is empty",



        self::MISSING_PARAMS_USERNAME_OR_PASSWORD => "missing params: username or password",
        self::MISSING_PARAMS_TAG_NAME => "missing params: tag name",
    ];

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const INTERNAL_SEVER_ERROR = 500;

    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 200;

    const USERNAME_OR_PASSWORD_ERROR = 1000;
    const USER_ROLE_INFO_ERROR = 1001;
    const TAG_IS_EMPTY = 1002;
    const TAG_TYPE_IS_EMPTY = 1003;

    const MISSING_PARAMS_USERNAME_OR_PASSWORD = 2000;
    const MISSING_PARAMS_TAG_NAME = 2001;

}