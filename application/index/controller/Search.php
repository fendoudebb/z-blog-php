<?php

namespace app\index\controller;


use app\common\util\ElasticsearchUtil;
use app\index\util\SidebarInfo;
use stdClass;


class Search extends Base {

    public function search($q) {
        $q = htmlspecialchars($q, ENT_NOQUOTES);
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 1;
        }
        $size = 20;
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
            return "error";
        }

        $took = $result->took;
        $hits = $result->hits->total->value;
        $this->request->__set("took", $took);
        $this->request->__set("hits", $hits);

        $arr = [
            'title' => "搜索-$q",
            'keywords' => "搜索关键词：$q",
            'description' => "搜索关键词：$q",
            'currentPage' => $page,
            'totalPage' => ceil($hits / $size),
            'hits' => $result->hits->hits
        ];
        $rankInfo = new SidebarInfo();
        $arr = array_merge($arr, $rankInfo->sidebarInfo());
        $compressHtml = compressHtml($this->fetch('search', $arr));
        return $compressHtml;
    }
}

/*
Elasticsearch cURL
curl -XGET "http://localhost:9200/post/_search?filter_path=-_shards,-timed_out,-hits.total.relation" -H 'Content-Type: application/json' -d'
{
  "_source": {
    "excludes": [
      "title",
      "content"
    ]
  },
  "from": 0,
  "size": 20,
  "min_score":0.1,
  "query": {
    "bool": {
      "must": [
        {
          "match": {
            "offline": false
          }
        }
      ],
      "should": [
        {
          "multi_match": {
            "query": "数据库",
            "fields": [
              "title",
              "content"
            ]
          }
        }
      ],
      "minimum_should_match": "50%"
    }
  },
  "highlight": {
    "order": "score",
    "pre_tags": [
      "<em>"
    ],
    "post_tags": [
      "</em>"
    ],
    "no_match_size": 150,
    "number_of_fragments": 1,
    "fragment_size": 80,
    "require_field_match": false,
    "fields": {
      "title": {},
      "content": {
        "number_of_fragments": 1,
        "fragment_size": 80
      }
    }
  }
}'


Empty return:
{
  "took" : 1,
  "hits" : {
    "total" : {
      "value" : 0
    },
    "max_score" : null,
    "hits" : [ ]
  }
}

Else return:
{
  "took" : 3,
  "hits" : {
    "total" : {
      "value" : 1
    },
    "max_score" : 2.8639252,
    "hits" : [
      {
        "_index" : "post",
        "_type" : "_doc",
        "_id" : "16",
        "_score" : 2.8639252,
        "_source" : {
          "offline" : false,
          "postTime" : "2019-04-03 19:52:41",
          "topics" : [ ],
          "postId" : 16
        },
        "highlight" : {
          "title" : [
            "asdsadas"
          ],
          "content" : [
            "https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html数字类型占用<em>字节数</em>TINYINT1"
          ]
        }
      }
    ]
  }
}
*/