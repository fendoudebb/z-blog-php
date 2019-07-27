<?php

namespace app\admin\controller;


class Administrator extends BaseRoleAdmin {

    public function info() {

    }

    public function add() {

    }

    public function edit() {//修改后注意清空redis登陆信息

    }

    public function audit() {//禁用或启用，登陆及token免登时需加逻辑判断（思路是redis信息hash中加一个isForbidden字段）

    }

}