<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\exception\SystemException;
use think\Log;

class BaseRoleNormal extends BaseAuth {

    public function _initialize() {
        parent::_initialize();
        if (!in_array("ROLE_ADMIN", $this->roles)) {
            if (!in_array("ROLE_NORMAL", $this->roles)) {
                Log::log("base role normal, permission not allowed, username[$this->username]");
                throw new SystemException(ResCode::FORBIDDEN);
            }
        }
    }

}