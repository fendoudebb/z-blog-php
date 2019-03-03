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
        self::USERNAME_DOES_NOT_EXIST => 'username does not exist',
        self::USERNAME_OR_PASSWORD_ERROR => 'username or password error',
        self::USER_ROLE_INFO_ERROR => "user's role info error",
        self::TOPIC_NAME_ALREADY_EXISTS => "topic name already exists",
        self::TOPIC_PARENT_ID_DOES_NOT_EXIST => "topic parent id does not exist",
        self::POST_TOPIC_DOES_NOT_EXIST => "post topic does not exist",
        self::POST_TOPIC_HAS_BEEN_DELETED => "post topic has been deleted",
        self::POST_TOPIC_ALREADY_EXIST => "post topic already exists",
        self::TOPIC_ID_DOES_NOT_EXIST => "topic id does not exist",
        self::POST_DOES_NOT_EXIST => "post id does not exist",
        self::OVER_POST_TOPIC_COUNT => "over post topic count",



        self::MISSING_PARAMS_USERNAME_OR_PASSWORD => "missing params: username or password",
        self::MISSING_PARAMS_TOPIC_NAME => "missing params: topic name",
        self::MISSING_PARAMS_TOPIC_PARENT_ID => "missing params: topic parent id",
        self::MISSING_PARAMS_POST_ID => "missing params: post id",
        self::MISSING_PARAMS_TOPIC_ID => "missing params: topic id",
        self::MISSING_PARAMS_TOPIC_SORT => "missing params: topic sort",



        self::ILLEGAL_ARGUMENT_POST_ID => "illegal argument: post id",
        self::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID => "illegal argument: topic parent id",
        self::ILLEGAL_ARGUMENT_TOPIC_ID => "illegal argument: topic id",
        self::ILLEGAL_ARGUMENT_TOPIC_SORT => "illegal argument: topic sort",
        self::ILLEGAL_ARGUMENT_AUDIT_STATUS => "illegal argument: audit status",



        self::COLLECTION_INSERT_FAIL => "collection insert fail",
        self::COLLECTION_UPDATE_FAIL => "collection update fail",
    ];

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const INTERNAL_SEVER_ERROR = 500;

    const URL_NOT_EXIST = -2;
    const REQUEST_FAIL = -1;
    const REQUEST_SUCCESS = 200;

    //---error code const start---
    const USERNAME_DOES_NOT_EXIST = 999;
    const USERNAME_OR_PASSWORD_ERROR = 1000;
    const USER_ROLE_INFO_ERROR = 1001;
    const TOPIC_NAME_ALREADY_EXISTS = 1002;
    const TOPIC_PARENT_ID_DOES_NOT_EXIST = 1003;
    const POST_TOPIC_DOES_NOT_EXIST = 1004;
    const POST_TOPIC_HAS_BEEN_DELETED = 1005;
    const POST_TOPIC_ALREADY_EXIST = 1006;
    const TOPIC_ID_DOES_NOT_EXIST = 1007;
    const POST_DOES_NOT_EXIST = 1008;
    const OVER_POST_TOPIC_COUNT = 1009;
    //---error code const end---

    //---missing params const start---
    const MISSING_PARAMS_USERNAME_OR_PASSWORD = 2000;
    const MISSING_PARAMS_TOPIC_NAME = 2001;
    const MISSING_PARAMS_TOPIC_PARENT_ID = 2002;
    const MISSING_PARAMS_POST_ID = 2003;
    const MISSING_PARAMS_TOPIC_ID = 2004;
    const MISSING_PARAMS_TOPIC_SORT = 2005;
    //---missing params const end---


    //---illegal argument const start---
    const ILLEGAL_ARGUMENT_POST_ID = 3000;
    const ILLEGAL_ARGUMENT_TOPIC_PARENT_ID = 3001;
    const ILLEGAL_ARGUMENT_TOPIC_ID = 3002;
    const ILLEGAL_ARGUMENT_TOPIC_SORT = 3003;
    const ILLEGAL_ARGUMENT_AUDIT_STATUS = 3004;
    //---illegal argument const end---


    const COLLECTION_INSERT_FAIL = 4000;
    const COLLECTION_UPDATE_FAIL = 4001;

}