<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;

class PostInfo extends BaseRoleNormal {

    public function postInfo() {
        $postId = intval(input('post.postId'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        $findPostCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => $postId
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'title' => 1,
                'postStatus' => 1,
                'content' => 1,
                'topics' => 1,
                'postProp' => 1
            ],
            'limit' => 1
        ];
        $postCmdArr = Mongo::cmd($findPostCmd);
        if (empty($postCmdArr)) {
            $this->log(ResCode::POST_DOES_NOT_EXIST);
            return $this->fail(ResCode::POST_DOES_NOT_EXIST);
        }
        $post = $postCmdArr[0];
        return $this->res($post);
    }

}