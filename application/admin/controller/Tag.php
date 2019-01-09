<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class Tag extends BaseAuth {

    public function tagInfo() {
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
                ->field('id, name')
                ->where('is_parent', 0)
                ->page($page, $size)
                ->select();
            if (empty($postType)) {
                Log::log("tag info, empty!, username[$this->username]");
                return $this->fail(ResCode::TAG_IS_EMPTY);
            }
            return $this->res($postType);
        } catch (Exception $e) {
            Log::log("tag info, exception->" . $e->getMessage());
            return $this->exception();
        }
    }

}