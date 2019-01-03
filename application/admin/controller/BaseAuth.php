<?php

namespace app\mini\controller;


use app\admin\config\RedisKey;
use app\admin\config\ResCode;
use app\common\exception\MsjException;
use app\common\util\Redis;
use think\Log;

abstract class BaseAuth extends Base {

    protected $uid;
    protected $ukId;
    protected $username;
    protected $nickname;
    protected $avatar;

    public function _initialize() {
        parent::_initialize();
        $token = $this->request->header('token');
        if (!isset($token)) {
            Log::log("base auth, lack of request header token!");
            throw new MsjException(ResCode::BAD_REQUEST);
        }
        $userId = Redis::init()->get(RedisKey::ADMIN_LOGIN_TOKEN . $token);
        if (!$userId) {
            Log::log("base auth, token isn't exist:token[$token]");
            throw new MsjException(ResCode::UNAUTHORIZED);
        }
        $this->uid = $userId;
        $hashKey = RedisKey::ADMIN_LOGIN_USER . $this->uid;
        $userInfo = Redis::init()->hMGet($hashKey,['uk_id', 'username', 'nickname', 'avatar']);
        if (!isset($userInfo)) {
            Log::log("base auth, without user info in cache, uid->" . $this->uid);
            throw new MsjException(ResCode::UNAUTHORIZED);
        }
        $this->ukId = $userInfo['uk_id'];
        $this->username = $userInfo['username'];
        $this->nickname = $userInfo['nickname'];
        $this->avatar = $userInfo['avatar'];
        Redis::init()->set(RedisKey::ADMIN_LOGIN_TOKEN . $token, $this->uid, RedisKey::ADMIN_LOGIN_TOKEN_EXPIRE_TIME);
    }

}