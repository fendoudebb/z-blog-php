<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class TagType extends BaseAuth {

    public function tagType() {
        try {
            $tagType = Db::table('tag')
                ->field('id, name')
                ->where('is_parent', 1)
                ->select();
            if (empty($tagType)) {
                Log::log("tag type, is empty, username[$this->username]");
                return $this->fail(ResCode::TAG_TYPE_IS_EMPTY);
            }
            return $this->res($tagType);
        } catch (Exception $e) {
            Log::log("tag type, exception->" . $e->getMessage());
            return $this->exception();
        }
    }

}