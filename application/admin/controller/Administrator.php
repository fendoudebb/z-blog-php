<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use stdClass;

class Administrator extends BaseRoleAdmin {

    public function info() {
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;

        $cmd = [
            'aggregate' => 'sys_user', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'username' => 1,
                        'password' => 1,
                        'roles' => 1,
                        'status' => 1,
                    ],
                ],
                [
                    '$sort' => ['_id' => 1]
                ],
                [
                    '$skip' => $offset
                ],
                [
                    '$limit' => $size
                ]
            ],
            'cursor' => new stdClass()
        ];
        $sysUser = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'sys_user'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['sysUser'] = $sysUser;
        return $this->res($response);
    }

    public function add() {
        $username = trim(strval(input("post.username")));
        $password = strval(input("post.password"));
        $roles = array_values(input("post.roles"));

        if (strlen($username) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_USERNAME);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_USERNAME);
        }
        if (strlen($password) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_PASSWORD);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_PASSWORD);
        }
        if (count($roles) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_ROLES);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_ROLES);
        }

        $insertAdministratorCmd = [
            'insert' => 'sys_user',
            'documents' => [
                [
                    'username' => $username,
                    'password' => $password,
                    'roles' => $roles,
                    'status' => 'NORMAL'
                ]
            ]
        ];
        $insertAdministratorResult = Mongo::cmd($insertAdministratorCmd);
        if (empty($insertAdministratorResult) || !$insertAdministratorResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();
    }

    public function edit() {//修改后注意清空redis登陆信息

    }

    public function audit() {//禁用或启用，登陆及token免登时需加逻辑判断（思路是redis信息hash中加一个isForbidden字段）

    }

}