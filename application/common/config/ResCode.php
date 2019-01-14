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
        self::TOPIC_NAME_EXISTS => "topic name exists already",



        self::MISSING_PARAMS_USERNAME_OR_PASSWORD => "missing params: username or password",
        self::MISSING_PARAMS_TOPIC_NAME => "missing params: topic name",
        self::MISSING_PARAMS_TOPIC_TYPE => "missing params: topic type",
        self::MISSING_PARAMS_POST_ID => "missing params: post id",



        self::ILLEGAL_ARGUMENT_TOPIC_TYPE => "illegal argument: topic type",



        self::TABLE_INSERT_FAIL => "table insert fail",
        self::TABLE_UPDATE_FAIL => "table update fail",
    ];

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const INTERNAL_SEVER_ERROR = 500;

    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 200;

    //---error code const start---
    const USERNAME_OR_PASSWORD_ERROR = 1000;
    const USER_ROLE_INFO_ERROR = 1001;
    const TOPIC_NAME_EXISTS = 1002;
    //---error code const end---

    //---missing params const start---
    const MISSING_PARAMS_USERNAME_OR_PASSWORD = 2000;
    const MISSING_PARAMS_TOPIC_NAME = 2001;
    const MISSING_PARAMS_TOPIC_TYPE = 2002;
    const MISSING_PARAMS_POST_ID = 2003;
    //---missing params const end---


    //---illegal argument const start---
    const ILLEGAL_ARGUMENT_TOPIC_TYPE = 3000;
    //---illegal argument const end---


    const TABLE_INSERT_FAIL = 4000;
    const TABLE_UPDATE_FAIL = 4001;

}