<?php

namespace app\admin\controller;


use app\admin\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Mongo;
use app\common\util\Redis;
use MongoDB\BSON\ObjectId;

class Login extends Base {

    public function login() {
        $username = input('post.username');
        $password = input('post.password');
        if (!isset($username) || !isset($password)) {
            $this->log(ResCode::MISSING_PARAMS_USERNAME_OR_PASSWORD);
            return $this->fail(ResCode::MISSING_PARAMS_USERNAME_OR_PASSWORD);
        }
        $cmd = [
            'find' => 'sys_user',
            'filter' => [
                'username' => $username,
            ],
            'projection' => [
                'password' => 1,
                'roles' => 1
            ],
            'limit' => 1
        ];
        $userCmdArr = Mongo::cmd($cmd);
        if (empty($userCmdArr)) {
            $this->log(ResCode::USERNAME_OR_PASSWORD_ERROR);
            return $this->fail(ResCode::USERNAME_OR_PASSWORD_ERROR);
        }
        $user = $userCmdArr[0];
        $objectId = new ObjectId($user->_id);
        $userId = $objectId->__toString();
        $roles = $user->roles;
        $pwd = $user->password;
        if ($password !== $pwd) {
            $this->log(ResCode::USERNAME_OR_PASSWORD_ERROR);
            return $this->fail(ResCode::USERNAME_OR_PASSWORD_ERROR);
        }
        $userInfo = [
            RedisKey::ADMIN_LOGIN_USER_INFO_ID => $userId,
            RedisKey::ADMIN_LOGIN_USER_INFO_USERNAME => $username,
            RedisKey::ADMIN_LOGIN_USER_INFO_ROLES => implode(",", $roles),
            RedisKey::ADMIN_LOGIN_USER_IP => $this->ip,
            RedisKey::ADMIN_LOGIN_USER_AGENT => $this->userAgent,
        ];
        $token = base64_encode($userId . ' ' . time());
        $pipeline = Redis::init()->multi(\Redis::PIPELINE);
        $loginUserKey = RedisKey::HASH_ADMIN_LOGIN_USER . $token;
        $pipeline->hMSet($loginUserKey, $userInfo);
        $pipeline->expire($loginUserKey, RedisKey::ADMIN_LOGIN_USER_EXPIRE_TIME);
        $pipeline->exec();
        return $this->res(['token' => $token, 'roles' => $roles]);

    }
}