<?php

use think\Route;

Route::get('/id', 'index/Index/index');

Route::post('/admin/login', 'admin/Login/login');

Route::post('/admin/tag', 'admin/Tag/tagInfo');

return [

];
