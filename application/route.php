<?php

use think\Route;

Route::any('/admin/insert/batch', 'admin/BatchInsert/batchInsert',['method'=>'get|post']);

Route::post('/admin/login', 'admin/Login/login');


//---Topic api start---
Route::post('/admin/topic', 'admin/Topic/topicList');
Route::post('/admin/topic/add', 'admin/TopicAdd/addTopic');
Route::post('/admin/topic/modify/sort', 'admin/TopicModifySort/modifyTopicSort');
Route::post('/admin/topic/modify/name', 'admin/TopicModifyName/modifyTopicName');
Route::post('/admin/topic/modify/parent', 'admin/TopicModifyParent/modifyTopicParent');
//---Topic api end---


//---Post api start---
Route::post('/admin/post', 'admin/Post/postList');
Route::post('/admin/post/topic', 'admin/PostTopic/postTopic');
Route::post('/admin/post/topic/modify', 'admin/PostModifyTopic/modifyPostTopic');
//---Post api end---

Route::get('/', 'index/Index/index');
Route::get('/example/js_control', 'index/Example/jsControl');

return [

];
