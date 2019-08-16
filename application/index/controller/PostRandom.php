<?php

namespace app\index\controller;


use app\index\util\RandomPostUtil;
use think\Log;

class PostRandom extends Base {

    public function randomPost() {
        $postId = intval(input("post.postId"));
        Log::log("postId#$postId");

        $randomPostUtil = new RandomPostUtil();
        $post = $randomPostUtil->getPostRandom($postId);
        return json(['code' => 200, 'msg' => '请求成功', 'data' => ['post' => $post]]);
    }

}
