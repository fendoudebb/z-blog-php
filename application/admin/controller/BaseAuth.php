<?php

namespace app\admin\controller;


use app\admin\config\RedisKey;
use app\common\config\ResCode;
use app\common\exception\SystemException;
use app\common\util\Redis;
use think\Log;

abstract class BaseAuth extends Base {

    protected $userId;
    protected $username;
    protected $nickname;
    protected $avatar;
    protected $roles;

    public function _initialize() {
        parent::_initialize();
        $token = $this->request->header('token');
        if (!isset($token)) {
            Log::log("base auth, lack of request header token, ip[$this->ip]");
            throw new SystemException(ResCode::BAD_REQUEST);
        }
        $loginUserKey = RedisKey::HASH_ADMIN_LOGIN_USER . $token;
        $isLogin = Redis::init()->exists($loginUserKey);
        if (!$isLogin) {
            Log::log("base auth, token isn't exist:token[$token]");
            throw new SystemException(ResCode::UNAUTHORIZED);
        }
        $hashKeys = [
            RedisKey::ADMIN_LOGIN_USER_INFO_ID,
            RedisKey::ADMIN_LOGIN_USER_INFO_USERNAME,
            RedisKey::ADMIN_LOGIN_USER_INFO_NICKNAME,
            RedisKey::ADMIN_LOGIN_USER_INFO_ROLES
        ];
        $userInfo = Redis::init()->hMGet($loginUserKey, $hashKeys);
        if (!isset($userInfo)) {
            Log::log("base auth, without user info in cache, uid[$this->userId]");
            throw new SystemException(ResCode::UNAUTHORIZED);
        }
        $this->userId = $userInfo[RedisKey::ADMIN_LOGIN_USER_INFO_ID];
        $this->username = $userInfo[RedisKey::ADMIN_LOGIN_USER_INFO_USERNAME];
        $this->nickname = $userInfo[RedisKey::ADMIN_LOGIN_USER_INFO_NICKNAME];
        $this->roles = explode(",", $userInfo[RedisKey::ADMIN_LOGIN_USER_INFO_ROLES]);
        Redis::init()->expire($loginUserKey, RedisKey::ADMIN_LOGIN_USER_EXPIRE_TIME);
    }

    public function log($code) {
        Log::log("[$this->url]-[$this->username]-> " . ResCode::$res_code[$code]);
    }

    public function logException($errorMsg) {
        Log::log("[$this->url]-[$this->username]-> $errorMsg");
    }

}