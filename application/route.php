<?php

use think\Route;


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
Route::post('/admin/post/comments', 'admin/PostComments/postComments');
Route::post('/admin/post/comment/switch', 'admin/PostCommentSwitch/switchPostComment');
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
//---Comment api end---

//---statistics api start---
Route::post('/admin/page_view', 'admin/PageView/pageView');
Route::post('/admin/ip_pool', 'admin/IpPool/ipPool');
//---statistics api end---

Route::get('/sitemap.xml', 'index/SitemapXML/sitemapXML');
Route::get('/404', 'index/RouterNotFound/routerNotFound', ['ext' => 'html']);

Route::get('/', 'index/Index/index');
Route::get('/message-board', 'index/MessageBoard/messageBoard', ['ext' => 'html']);
Route::get('/p/:postId', 'index/Post/post', ['ext' => 'html'], ['postId' => '\d+']);
Route::get('/topic/:topic', 'index/Topic/topic', ['ext' => 'html']);
Route::get('/example/js_control', 'index/Example/jsControl', ['ext' => 'html']);
Route::get('/tool/json/format', 'index/Tool/formatJson', ['ext' => 'html']);

Route::post('/post/like', 'index/PostLike/likePost');
Route::post('/post/comment', 'index/PostComment/postComment');
Route::post('/leave-a-message', 'index/MessageLeave/leaveMessage');

return [

];
