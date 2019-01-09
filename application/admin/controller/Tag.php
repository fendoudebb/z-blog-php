<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class Tag extends BaseAuth {

    public function info() {
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($page)) {
            $page = 1;
        }
        if (!isset($size) || $size >= 20) {
            $size = 20;
        }
        try {
            $postType = Db::table('tag')
                ->field('tid, name')
                ->where('is_parent', 0)
                ->page($page, $size)
                ->select();
            if (empty($postType)) {
                Log::log("list post_type, empty!, username[$this->username]");
                return $this->fail(ResCode::POST_TYPE_IS_EMPTY);
            }
            return $this->res($postType);
        } catch (Exception $e) {
            Log::log("list post_type, exception->" . $e->getMessage());
            return $this->fail(ResCode::REQUEST_FAIL);
        }
    }

}