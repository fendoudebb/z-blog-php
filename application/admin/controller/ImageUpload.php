<?php

namespace app\admin\controller;


class ImageUpload extends BaseRoleAdmin {

    public function uploadImage() {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $uploadImgDir = '/uploads/img/';

        $info = $file->validate(['size'=>1024000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . $uploadImgDir);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            $domain = request()->domain();
            return $this->res($domain.$uploadImgDir.$info->getSaveName());
        }else{
            // 上传失败获取错误信息
            return $this->fail($file->getError());
        }
    }

}