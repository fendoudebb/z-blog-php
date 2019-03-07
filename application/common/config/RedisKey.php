<?php

namespace app\common\config;


class RedisKey {

    const HYPER_IP = 'zblog_hyper_ip';
    const STR_PV = 'zblog_str_pv';

    const HASH_STATISTICS = 'zblog_hash_statistics';
    const STATISTICS_POST_BACKEND = 'post_backend';
    const STATISTICS_POST_FRONTEND = 'post_frontend';

    const STR_404_HTML = 'zblog_str_404_html';
    const SET_VISIBLE_POST = 'zblog_set_visible_post';

    const HASH_POST_DETAIL = 'zblog_hash_post_detail:';

    const POST_TITLE = 'title';
    const POST_KEYWORDS = 'keywords';
    const POST_DESC = 'description';
    const POST_STATUS = 'status';
    const POST_TIME = 'postTime';
    const POST_IS_PRIVATE = 'isPrivate';
    const POST_IS_COMMENT_CLOSE = 'isCommentClose';
    const POST_IS_COPY = 'isCopy';
    const POST_ORIGINAL_LINK = 'originalLink';
    const POST_PV = 'pv';
    const POST_COMMENT_COUNT = 'commentCount';
    const POST_LIKE_COUNT = 'likeCount';
    const POST_HTML = 'content';
    const POST_TOPIC = 'topic';

}