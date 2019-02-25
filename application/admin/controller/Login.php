<?php

namespace app\admin\controller;


use app\admin\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Redis;
use think\Db;
use think\Exception;

class Login extends Base {

    public function login() {
        $username = input('post.username');
        $password = input('post.password');
        if (!isset($username) || !isset($password)) {
            $this->log(ResCode::MISSING_PARAMS_USERNAME_OR_PASSWORD);
            return $this->fail(ResCode::MISSING_PARAMS_USERNAME_OR_PASSWORD);
        }
        try {
            $sysUser = Db::table('sys_user')
                ->field(['id' => 1, 'nickname' => 1, 'roles' => 1])
                ->where('username', $username)
                ->where('password', $password)
                ->find();
            if (!isset($sysUser)) {
                $this->log(ResCode::USERNAME_OR_PASSWORD_ERROR);
                return $this->fail(ResCode::USERNAME_OR_PASSWORD_ERROR);
            }
            $userId = $sysUser['id'];
            $nickname = $sysUser['nickname'];
            $roles = $sysUser['roles'];
            if (empty($roles)) {
                $this->log(ResCode::USER_ROLE_INFO_ERROR);
                return $this->fail(ResCode::USER_ROLE_INFO_ERROR);
            }
            $userInfo = [
                RedisKey::ADMIN_LOGIN_USER_INFO_ID => $userId,
                RedisKey::ADMIN_LOGIN_USER_INFO_USERNAME => $username,
                RedisKey::ADMIN_LOGIN_USER_INFO_NICKNAME => $nickname,
                RedisKey::ADMIN_LOGIN_USER_INFO_ROLES => implode(",", $roles),
            ];
            $token = base64_encode($userId . ' ' . time());
            $pipeline = Redis::init()->multi(\Redis::PIPELINE);
            $loginUserKey = RedisKey::HASH_ADMIN_LOGIN_USER . $token;
            $pipeline->hMSet($loginUserKey, $userInfo);
            $pipeline->expire($loginUserKey, RedisKey::ADMIN_LOGIN_USER_EXPIRE_TIME);
            $pipeline->exec();
            return $this->res(['token' => $token, 'roles' => $roles]);
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return $this->exception();
        }
    }
}