<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\util\Redis;
use app\index\util\SidebarInfo;

class WebInfo extends BaseRoleNormal {

    public function webInfo() {
        $webInfo = Redis::init()->get(RedisKey::WEB_INFO);
        if ($webInfo) {
            $arr = unserialize($webInfo);
        } else {
            $sidebarInfo = new SidebarInfo();
            $arr = $sidebarInfo->sidebarInfo();
        }
        return $this->res($arr);
    }


}