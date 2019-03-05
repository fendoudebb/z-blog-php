<?php

namespace app\admin\controller;


use app\admin\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Redis;

class Logout extends BaseAuth {

    public function logout() {
        $token = $this->request->header('token');
        if (!isset($token)) {
            $this->log(ResCode::BAD_REQUEST);
            return $this->fail(ResCode::BAD_REQUEST);
        }
        $loginUserKey = RedisKey::HASH_ADMIN_LOGIN_USER . $token;
        $isLogin = Redis::init()->exists($loginUserKey);
        if (!$isLogin) {
            $this->log(ResCode::UNAUTHORIZED);
            return $this->fail(ResCode::UNAUTHORIZED);
        }
        $result = Redis::init()->del($loginUserKey);
        if ($result === false) {
            $this->log(ResCode::REQUEST_FAIL);
            return $this->fail(ResCode::REQUEST_FAIL);
        }
        return $this->res();
    }

}