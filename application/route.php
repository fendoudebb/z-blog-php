<?php

use think\Route;

Route::get('/404', 'index/RouterNotFound/routerNotFound', ['ext' => 'html']);

Route::post('/admin/login', 'admin/Login/login');
Route::post('/admin/logout', 'admin/Logout/logout');


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

Route::get('/', 'index/Index/index');
Route::get('/p/:postId', 'index/Post/post', ['ext' => 'html'], ['postId' => '\d+']);
Route::get('/example/js_control', 'index/Example/jsControl', ['ext' => 'html']);
Route::get('/tool/json/format', 'index/Tool/formatJson', ['ext' => 'html']);

Route::post('/post/like', 'index/PostLike/likePost');

return [

];
