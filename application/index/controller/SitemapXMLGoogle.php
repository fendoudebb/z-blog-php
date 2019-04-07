<?php

namespace app\index\controller;

//https://ziyuan.baidu.com/college/courseinfo?id=267&page=2
/*<url>
<loc>http://m.example.com/index.html</loc>
<mobile:mobile type="mobile"/>
<lastmod>2009-12-14</lastmod>
<changefreq>daily</changefreq>
<priority>0.8</priority>
</url> */

//修改了Xml代码，需注意 \think\response\Xml

use app\common\config\RedisKey;
use app\common\util\Mongo;
use app\common\util\Redis;
use think\Log;

class SitemapXMLGoogle extends Base {

    public function sitemapXMLGoogle() {
        $options = [
            "root_node" => "urlset",
            "root_attr" => [
                "xmlns" => "http://www.sitemaps.org/schemas/sitemap/0.9",
            ],
            "item_node" => "url",
            "id" => ""
        ];
        $cacheSitemapXML = Redis::init()->get(RedisKey::SITEMAP_XML_GOOGLE);
        if ($cacheSitemapXML) {
            $data = json_decode($cacheSitemapXML);
        } else {
            $rootUrl = 'https://' . request()->host();
            $today = date('c');//Y-m-d\TH:i:s.0000\Z
            $data = [
                [
                    'loc' => $rootUrl,
                    'lastmod' => $today,
                    'changefreq' => 'always',
                    'priority' => 1
                ],
                [
                    'loc' => $rootUrl.'/message-board.html',
                    'lastmod' => $today,
                    'changefreq' => 'always',
                    'priority' => 1
                ],
                [
                    'loc' => $rootUrl.'/tool/json/format.html',
                    'lastmod' => $today,
                    'changefreq' => 'daily',
                    'priority' => 0.8
                ]
            ];
            $sitemapPostsCmd = [
                'find' => 'post',
                'filter' => [
                    'postStatus' => 'ONLINE',
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                ],
                'sort' => [
                    '_id' => 1
                ],
                'limit' => 10000,
            ];
            $sitemapPostsCmdArr = Mongo::cmd($sitemapPostsCmd);
            foreach ($sitemapPostsCmdArr as $post) {
                $sitemap = [
                    'loc' => $rootUrl . '/p/'.$post->postId . '.html',
                    'lastmod' => $today,
                    'changefreq' => 'daily',
                    'priority' => 0.8
                ];
                array_push($data, $sitemap);
            }
            Redis::init()->set(RedisKey::SITEMAP_XML_GOOGLE, json_encode($data));
        }
        return xml($data, 200, [], $options);
    }

}