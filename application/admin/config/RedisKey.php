<?php

namespace app\admin\config;


class RedisKey {
    const ADMIN_LOGIN_USER_EXPIRE_TIME = 604800;//7天

    const HASH_ADMIN_LOGIN_USER = 'zblog_hash_admin_login_user:';
    const ADMIN_LOGIN_USER_INFO_ID = 'id';
    const ADMIN_LOGIN_USER_INFO_USERNAME = 'username';
    const ADMIN_LOGIN_USER_INFO_ROLES = 'roles';
    const ADMIN_LOGIN_USER_IP = 'ip';
    const ADMIN_LOGIN_USER_AGENT = 'ua';


}