<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class PostType extends BaseAuth {

    public function listType() {
        $page = input('post.page');
        $size = input('post.size');
        try {
            $postType = Db::table('post_type')
                ->field('name')
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