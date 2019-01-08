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
        self::USERNAME_OR_PASSWORD_EMPTY => 'username or password is empty',
        self::USER_ROLE_INFO_ERROR => "user's role info error",
        self::POST_TYPE_IS_EMPTY => "post type is empty",
    ];

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const INTERNAL_SEVER_ERROR = 500;

    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 0;

    const USERNAME_OR_PASSWORD_ERROR = 100;
    const USERNAME_OR_PASSWORD_EMPTY = 101;
    const USER_ROLE_INFO_ERROR = 102;
    const POST_TYPE_IS_EMPTY = 103;

}