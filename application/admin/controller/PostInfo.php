<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use think\Db;

class PostInfo extends BaseRoleNormal {

    public function postInfo() {
        $postId = input('post.postId');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        $findPostCmd = [
            'find' => 'post',
            'filter' => [
                '_id' => new ObjectId($postId)
            ],
            'projection' => [
                '_id' => 1,
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
        $objectId = $post->_id;
        if (is_object($objectId) && method_exists($objectId, '__toString')) {
            $id = $objectId->__toString();
            $post->id = $id;
            unset($post->_id);
        };
        return $this->res($post);
    }

}