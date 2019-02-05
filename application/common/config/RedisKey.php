<?php

namespace app\common\config;


class RedisKey {

    const HYPER_IP = 'msj_hyper_ip';

    const HASH_STATISTICS = 'msj_hash_statistics';

    const SET_NONEXISTENT_POST = 'msj_nonexistent_post';

    const HASH_POST_HTML = 'msj_hash_post_html';
    const HASH_POST_DETAIL = 'msj_hash_post_detail:';

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

}