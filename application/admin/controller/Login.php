<?php

namespace app\admin\controller;


use app\admin\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Redis;
use think\Db;
use think\Exception;
use think\Log;

class Login extends Base {

    public function login() {
        $username = input('post.username');
        $password = input('post.password');
        if (!isset($username) || !isset($password)) {
            Log::log("admin login, username or password field is null, ip[$this->ip]");
            return $this->fail(ResCode::USERNAME_OR_PASSWORD_EMPTY);
        }
        try {
            $sysUser = Db::table('sys_user')
                ->field('id, uid, nickname, avatar')
                ->where('username', $username)
                ->where('password', $password)
                ->find();
            if (!isset($sysUser)) {
                Log::log("admin login, user[$username] isn't not exist");
                return $this->fail(ResCode::USERNAME_OR_PASSWORD_ERROR);
            }
            $roles = Db::table('sys_user_role rel')
                ->join('sys_user u', 'rel.uid = u.id')
                ->join('sys_role r', 'rel.rid = r.id')
                ->where('u.username', $username)
                ->column('r.name as roleName');
            if (empty($roles)) {
                Log::log("admin login, user's[$username] role is empty");
                return $this->fail(ResCode::USERNAME_ROLE_INFO_ERROR);
            }
            $userId = $sysUser['id'];
            $uid = $sysUser['uid'];
            $nickname = $sysUser['nickname'];
            $avatar = $sysUser['avatar'];
            $userInfo = [
                RedisKey::ADMIN_LOGIN_USER_INFO_UID => $uid,
                RedisKey::ADMIN_LOGIN_USER_INFO_NICKNAME => $nickname,
                RedisKey::ADMIN_LOGIN_USER_INFO_AVATAR => $avatar,
                RedisKey::ADMIN_LOGIN_USER_INFO_ROLES => implode(",", $roles),
            ];
            Redis::init()->hMSet(RedisKey::ADMIN_LOGIN_USER . $userId, $userInfo);
            $token = base64_encode($userId . ' ' . time());
            Redis::init()->setex(RedisKey::ADMIN_LOGIN_USER . $token, RedisKey::ADMIN_LOGIN_TOKEN_EXPIRE_TIME, $userId);
            return $this->res(['token' => $token, 'roles' => $roles]);
        } catch (Exception $e) {
            Log::log("admin login, username[$username]-password[$password], exception->" . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }
}