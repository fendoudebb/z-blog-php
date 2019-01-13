<?php

use think\Route;

Route::get('/id', 'index/Index/index');
Route::any('/admin/insert/batch', 'admin/BatchInsert/batchInsert',['method'=>'get|post']);

Route::post('/admin/login', 'admin/Login/login');


//---Topic api start---
Route::post('/admin/topic', 'admin/Topic/topicList');
Route::post('/admin/topic/add', 'admin/TopicAdd/addTopic');
//---Topic api end---


//---Post api start---
Route::post('/admin/post', 'admin/Post/postList');
//---Post api end---

return [

];
