<?php

namespace app\index\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use app\index\util\RankInfo;

class Post extends Base {

    public function post($postId) {
        $postCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => intval($postId),
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'title' => 1,
                'description' => 1,
                'postTime' => 1,
                'contentHtml' => 1,
                'postStatus' => 1,
                'commentStatus' => 1,
                'postProp' => 1,
                'postWordCount' => 1,
                'topics' => 1,
                'pv' => 1,
                'commentCount' => 1,
                'likeCount' => 1
            ],
            'limit' => 1
        ];
        $postCmdArr = Mongo::cmd($postCmd);
        if (empty($postCmdArr)) {
            $this->log(ResCode::POST_DOES_NOT_EXIST);
            return redirect('/404.html');
        }
        $post = $postCmdArr[0];
        $postStatus = $post->postStatus;
        if ($postStatus !== 'ONLINE') {
            $this->log(ResCode::POST_IS_NOT_ONLINE);
            return redirect('/404.html');
        }

        $arr = [
            'title' => $post->title,
            'description' => $post->description,
            'post' => $post,
        ];
        if (isset($post->topics) && !empty($post->topics)) {
            $arr['keywords'] = implode(",", $post->topics) . ";$post->title";
        } else {
            $arr['keywords'] = $post->title;
        }

        $postLikeExistCmd = [
            'find' => 'post',
            'filter' => [
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
        $arr['isLiked'] = !empty($existResult);

        $rankInfo = new RankInfo();
        $arr = array_merge($arr, $rankInfo->rankInfo());

        $compressHtml = compressHtml($this->fetch('post', $arr));
        return $compressHtml;
    }

}