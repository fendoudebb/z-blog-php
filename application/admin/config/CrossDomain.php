<?php

namespace app\admin\config;


use think\Log;

class CrossDomain {
    public function appInit(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token, Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');

        if(request()->isOptions()){
            Log::log('CrossDomain option request');
            exit();
        }
    }
}