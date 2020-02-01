<?php

use think\Route;

Route::get("/es/import/mongo_data", "index/EsImportMongoData/import");

Route::post('/admin/login', 'admin/Login/login');
Route::post('/admin/logout', 'admin/Logout/logout');

Route::post('/admin/web_info', 'admin/WebInfo/webInfo');

//---Post api start---
Route::post('/admin/post', 'admin/Post/postList');
Route::post('/admin/post/info', 'admin/PostInfo/postInfo');
Route::post('/admin/post/publish', 'admin/PostPublish/publishPost');
Route::post('/admin/post/audit', 'admin/PostAudit/auditPost');
Route::post('/admin/post/topic/add', 'admin/PostTopicAdd/addPostTopic');
Route::post('/admin/post/topic/delete', 'admin/PostTopicDelete/deletePostTopic');
Route::post('/admin/post/comment', 'admin/PostComment/postComment');
Route::post('/admin/post/comment/delete', 'admin/PostCommentDelete/deletePostComment');
Route::post('/admin/post/comment/switch', 'admin/PostCommentSwitch/switchPostComment');
Route::post('/admin/post/comment/reply', 'admin/PostCommentReply/replyPostComment');
Route::post('/admin/img/upload', 'admin/ImageUpload/uploadImage');
//---Post api end---

//---Topic api start---
Route::post('/admin/topic', 'admin/Topic/topic');
Route::post('/admin/topic/add', 'admin/TopicAdd/addTopic');
Route::post('/admin/topic/delete', 'admin/TopicDelete/deleteTopic');
Route::post('/admin/topic/sort/modify', 'admin/TopicSortModify/modifyTopicSort');
Route::post('/admin/topic/name/modify', 'admin/TopicNameModify/modifyTopicName');
//---Topic api end---

//---English api start---
Route::post('/admin/english', 'admin/English/englishList');
Route::post('/admin/english/add', 'admin/English/addEnglish');
Route::post('/admin/english/update', 'admin/English/updateEnglish');
//---English api end---

//---Comment api start---
Route::post('/admin/message/board', 'admin/MessageBoard/messageBoard');
Route::post('/admin/message/delete', 'admin/MessageDelete/deleteMessage');
Route::post('/admin/message/reply', 'admin/MessageReply/replyMessage');
//---Comment api end---

//---Links api start---
Route::post('/admin/links', 'admin/Links/links');
Route::post('/admin/links/add', 'admin/LinksAdd/addLink');
Route::post('/admin/links/audit', 'admin/LinksAudit/auditLink');
Route::post('/admin/links/edit', 'admin/LinksEdit/editLink');
Route::post('/admin/links/modify/sort', 'admin/LinksSortModify/modifyTopicSort');
//---Links api end---

//---statistics api start---
Route::post('/admin/page_view', 'admin/PageView/pageView');
Route::post('/admin/ip_pool', 'admin/IpPool/ipPool');
Route::post('/admin/ip_unrecognized', 'admin/IpPool/unrecognizedIp');
Route::post('/admin/ip_unrecognized/query', 'admin/IpPool/queryUnrecognizedIp');
Route::get('/admin/ip_unrecognized/schedule', 'admin/IpPoolSchedule/queryUnrecognizedIp');
Route::post('/admin/search_stats', 'admin/SearchStats/searchStats');
//---statistics api end---

//---rank api start---
Route::post('/admin/rank/pv', 'admin/Rank/pv');
Route::post('/admin/rank/likes', 'admin/Rank/likes');
Route::post('/admin/rank/comments', 'admin/Rank/comments');
//---rank api end---

//---administrator api start---
Route::post('/admin/administrator/info', 'admin/Administrator/info');
Route::post('/admin/administrator/add', 'admin/Administrator/add');
Route::post('/admin/administrator/edit', 'admin/Administrator/edit');
Route::post('/admin/administrator/audit', 'admin/Administrator/audit');
//---administrator api end---

Route::get('/sitemap.xml', 'index/SitemapXML/sitemapXML');
Route::get('/sitemap_google.xml', 'index/SitemapXMLGoogle/sitemapXMLGoogle');
Route::get('/404', 'index/RouterNotFound/routerNotFound', ['ext' => 'html']);

Route::get('/', 'index/Index/index');
Route::get('/message-board', 'index/MessageBoard/messageBoard', ['ext' => 'html']);
Route::get('/english', 'index/English/english', ['ext' => 'html']);
Route::get('/p/:postId', 'index/Post/post', ['ext' => 'html'], ['postId' => '\d+']);
Route::get('/topic/:topic', 'index/Topic/topic', ['ext' => 'html']);
Route::get('/search/:q', 'index/Search/search', ['ext' => 'html']);
Route::get('/example/js_control', 'index/Example/jsControl', ['ext' => 'html']);
Route::get('/tool/format/json', 'index/Tool/formatJson', ['ext' => 'html']);
Route::get('/tool/format/timestamp', 'index/Tool/formatTimestamp', ['ext' => 'html']);
Route::get('/tool/query/ip', 'index/Tool/queryIp', ['ext' => 'html']);

Route::post('/post/like', 'index/PostLike/likePost');
Route::post('/post/comment', 'index/PostComment/postComment');
Route::post('/post/random', 'index/PostRandom/randomPost');
Route::post('/leave-a-message', 'index/MessageLeave/leaveMessage');
Route::post('/query/ip', 'index/QueryIp/queryIp');
Route::post('/query/result', 'index/QueryIp/queryResult');


Route::get('/wechat/push', 'wechat/WechatPush/push');

Route::get('/m/index', 'mobile/MobileIndex/index');
Route::get('/m/p/:postId', 'mobile/MobilePost/post', ['postId' => '\d+']);
Route::get('/m/search/:q', 'mobile/MobileSearch/search');

return [

];
