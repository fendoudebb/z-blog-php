<?php

namespace app\admin\config;


class RedisKey {
    const ADMIN_LOGIN_TOKEN = 'msj_admin_login_token:';
    const ADMIN_LOGIN_TOKEN_EXPIRE_TIME = 604800;//7天
    const ADMIN_LOGIN_USER = 'msj_admin_login_user:';
    const ADMIN_LOGIN_USER_INFO_ID = 'id';
    const ADMIN_LOGIN_USER_INFO_UID = 'uid';
    const ADMIN_LOGIN_USER_INFO_USERNAME = 'username';
    const ADMIN_LOGIN_USER_INFO_NICKNAME = 'nickname';
    const ADMIN_LOGIN_USER_INFO_AVATAR = 'avatar';
    const ADMIN_LOGIN_USER_INFO_ROLES = 'roles';


}