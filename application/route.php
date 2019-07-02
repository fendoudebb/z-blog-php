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
Route::post('/admin/img/upload', 'admin/ImageUpload/uploadImage');
//---Post api end---

//---Post api start---
Route::post('/admin/topic', 'admin/Topic/topic');
Route::post('/admin/topic/add', 'admin/TopicAdd/addTopic');
Route::post('/admin/topic/delete', 'admin/TopicDelete/deleteTopic');
Route::post('/admin/topic/sort/modify', 'admin/TopicSortModify/modifyTopicSort');
Route::post('/admin/topic/name/modify', 'admin/TopicNameModify/modifyTopicName');
//---Post api end---

//---Comment api start---
Route::post('/admin/message/board', 'admin/MessageBoard/messageBoard');
Route::post('/admin/message/delete', 'admin/MessageDelete/deleteMessage');
Route::post('/admin/message/reply', 'admin/MessageReply/replyMessage');
//---Comment api end---

//---Links api start---
Route::post('/admin/links', 'admin/Links/links');
Route::post('/admin/links/add', 'admin/LinksAdd/addLinks');
Route::post('/admin/links/audit', 'admin/LinksAudit/auditLinks');
//---Links api end---

//---statistics api start---
Route::post('/admin/page_view', 'admin/PageView/pageView');
Route::post('/admin/ip_pool', 'admin/IpPool/ipPool');
Route::post('/admin/ip_unrecognized', 'admin/IpPool/unrecognizedIp');
Route::post('/admin/search_stats', 'admin/SearchStats/searchStats');
//---statistics api end---

Route::get('/sitemap.xml', 'index/SitemapXML/sitemapXML');
Route::get('/sitemap_google.xml', 'index/SitemapXMLGoogle/sitemapXMLGoogle');
Route::get('/404', 'index/RouterNotFound/routerNotFound', ['ext' => 'html']);

Route::get('/', 'index/Index/index');
Route::get('/message-board', 'index/MessageBoard/messageBoard', ['ext' => 'html']);
Route::get('/p/:postId', 'index/Post/post', ['ext' => 'html'], ['postId' => '\d+']);
Route::get('/topic/:topic', 'index/Topic/topic', ['ext' => 'html']);
Route::get('/search/:q', 'index/Search/search', ['ext' => 'html']);
Route::get('/example/js_control', 'index/Example/jsControl', ['ext' => 'html']);
Route::get('/tool/format/json', 'index/Tool/formatJson', ['ext' => 'html']);
Route::get('/tool/format/timestamp', 'index/Tool/formatTimestamp', ['ext' => 'html']);
Route::get('/tool/query/ip', 'index/Tool/queryIp', ['ext' => 'html']);

Route::post('/post/like', 'index/PostLike/likePost');
Route::post('/post/comment', 'index/PostComment/postComment');
Route::post('/leave-a-message', 'index/MessageLeave/leaveMessage');
Route::post('/query/ip', 'index/QueryIp/queryIp');
Route::post('/query/result', 'index/QueryIp/queryResult');

return [

];
