<?php

namespace app\index\controller;


class RouterNotFound extends Base {

    public function routerNotFound() {
        $arr = [
            'title' => '您找的页面飞走了~~',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，麦司机',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
        ];
        return compressHtml($this->fetch('public/404',$arr));
    }

}