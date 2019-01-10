<?php

use think\Route;

Route::get('/id', 'index/Index/index');

Route::post('/admin/login', 'admin/Login/login');


//---Topic api start---
Route::post('/admin/topic', 'admin/Topic/topicInfo');
Route::post('/admin/topic/add', 'admin/TopicAdd/addTopic');
//---Topic api end---

return [

];
