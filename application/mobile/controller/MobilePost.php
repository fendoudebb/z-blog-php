<?php

namespace app\mobile\controller;


use app\admin\controller\Base;
use app\common\config\ResCode;
use app\common\util\Mongo;
use think\Log;

class MobilePost extends Base {

    function post($postId) {
        $postCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => intval($postId),
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'title' => 1,
                'postTime' => 1,
                'contentHtml' => 1,
                'postStatus' => 1,
                'commentStatus' => 1,
                'postWordCount' => 1,
                'topics' => 1,
                'pv' => 1,
                'commentCount' => 1,
                'likeCount' => 1,
                'postComment.nickname' => 1,
                'postComment.commentTime' => 1,
                'postComment.floor' => 1,
                'postComment.status' => 1,
                'postComment.browser' => 1,
                'postComment.os' => 1,
            ],
            'limit' => 1
        ];
        Log::log($postCmd);
        $postCmdArr = Mongo::cmd($postCmd);
        Log::log($postCmdArr);
        if (empty($postCmdArr)) {
            $this->log(ResCode::POST_DOES_NOT_EXIST);
            return $this->fail(ResCode::POST_DOES_NOT_EXIST);
        }
        $post = $postCmdArr[0];
        $postStatus = $post->postStatus;
        if ($postStatus !== 'ONLINE') {
            $this->log(ResCode::POST_IS_NOT_ONLINE);
            return $this->fail(ResCode::POST_IS_NOT_ONLINE);
        }

        $arr = [
            'post' => $post,
        ];

        /*$postLikeExistCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => intval($postId),
                'postLike' => [
                    '$elemMatch' => [
                        'ip' => $this->ip
                    ]
                ]
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1
            ]
        ];
        $existResult = Mongo::cmd($postLikeExistCmd);
        $arr['isLiked'] = !empty($existResult);*/

        /*$randomPostUtil = new RandomPostUtil();
        $randomPost = $randomPostUtil->getPostRandom($postId);
        $arr['randomPosts'] = $randomPost;*/

//        $compressHtml = compressHtml($this->fetch('post', $arr));
        return $this->res($arr);
    }

}