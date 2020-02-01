<?php

namespace app\mobile\controller;


use app\admin\controller\Base;
use app\common\config\ResCode;
use app\common\util\ElasticsearchUtil;
use stdClass;

class MobileSearch extends Base {

    public function search($q) {
        $q = htmlspecialchars($q, ENT_NOQUOTES);
        $page = intval(input('get.page'));
        $size = intval(input('get.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;

        $param = [
            "_source" => [
                "excludes" => [
                    "title",
                    "content"
                ]
            ],
            "from" => $offset,
            "size" => 20,
            "min_score" => 0.1,
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "match" => [
                                "offline" => false
                            ]
                        ]
                    ],
                    "should" => [
                        [
                            "multi_match" => [
                                "query" => $q,
                                "fuzziness" => "AUTO",//模糊查询，自动修正（可以设置成0,1,2等）
                                "fields" => [
                                    "title",
                                    "content"
                                ]
                            ]
                        ]
                    ],
                    "minimum_should_match" => "50%"
                ]
            ],
            "highlight" => [
                "order" => "score",
                "pre_tags" => [
                    "<em>"
                ],
                "post_tags" => [
                    "</em>"
                ],
                "no_match_size" => 150,
                "number_of_fragments" => 1,
                "fragment_size" => 100,
                "require_field_match" => false,
                "fields" => [
                    "title" => new stdClass(),
                    "content" => new stdClass()
                ]
            ]
        ];

        $result = ElasticsearchUtil::GET("http://localhost:9200/post/_search?filter_path=-_shards,-timed_out,-hits.total.relation", $param);
        if (isset($result->error)) {
            return $this->fail(ResCode::INTERNAL_SEVER_ERROR);
        }

        $took = $result->took;
        $hits = $result->hits->total->value;
        $this->request->__set("took", $took);
        $this->request->__set("hits", $hits);

        $temp = [];

        foreach ($result->hits->hits as $hit) {
            $post = [
                'postId' => $hit->_source->postId,
                'postTime' => $hit->_source->postTime,
                'topics' => $hit->_source->topics,
                'title' => str_replace("</em><em>", "</em> <em>", $hit->highlight->title[0]),
                'content' => str_replace("</em><em>", "</em> <em>", $hit->highlight->content[0]),
            ];
            $temp[] = $post;
        }

        $arr = [
            'currentPage' => $page,
            'totalPage' => ceil($hits / $size),
            'hits' => $temp
        ];
//        return str_replace("</em><em>", "</em> <em>", $compressHtml);
        return $this->res($arr);
    }

}