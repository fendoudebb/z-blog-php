<?php

namespace app\mini\controller;


use app\admin\config\RedisKey;
use app\admin\config\ResCode;
use app\common\exception\SystemException;
use app\common\util\Redis;
use think\Log;

abstract class BaseAuth extends Base {

    protected $userId;
    protected $uid;
    protected $username;
    protected $nickname;
    protected $avatar;

    public function _initialize() {
        parent::_initialize();
        $token = $this->request->header('token');
        if (!isset($token)) {
            Log::log("base auth, lack of request header token!");
            throw new SystemException(ResCode::BAD_REQUEST);
        }
        $userId = Redis::init()->get(RedisKey::ADMIN_LOGIN_TOKEN . $token);
        if (!$userId) {
            Log::log("base auth, token isn't exist:token[$token]");
            throw new SystemException(ResCode::UNAUTHORIZED);
        }
        $this->userId = $userId;
        $hashKey = RedisKey::ADMIN_LOGIN_USER . $this->userId;
        $userInfo = Redis::init()->hMGet($hashKey,['uid', 'username', 'nickname', 'avatar']);
        if (!isset($userInfo)) {
            Log::log("base auth, without user info in cache, uid->" . $this->uid);
            throw new SystemException(ResCode::UNAUTHORIZED);
        }
        $this->uid = $userInfo['uid'];
        $this->username = $userInfo['username'];
        $this->nickname = $userInfo['nickname'];
        $this->avatar = $userInfo['avatar'];
        Redis::init()->set(RedisKey::ADMIN_LOGIN_TOKEN . $token, $this->userId, RedisKey::ADMIN_LOGIN_TOKEN_EXPIRE_TIME);
    }

}