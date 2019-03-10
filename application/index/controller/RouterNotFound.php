<?php

namespace app\index\controller;


use app\common\config\RedisKey;
use app\common\util\Redis;

class RouterNotFound extends Base {

    public function routerNotFound() {
        $compressHtml = Redis::init()->get(RedisKey::STR_404_HTML);
        if ($compressHtml === false) {
            $arr = [
                'title' => '您找的页面飞走了~~',
                'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，Z-Blog',
                'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            ];
            $compressHtml = compressHtml($this->fetch('public/404', $arr));
            Redis::init()->set(RedisKey::STR_404_HTML, $compressHtml);
        }
        return $compressHtml;
    }

}