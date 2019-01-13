## 0. Common 通用
### 0.1 Headers  请求头
#### Http Method
POST
#### Content-Type
```http request
Content-Type:application/json;charset=utf‐8
```

#### token(Except Login API / 除了login接口外都需要在请求头中加入token)
```http request
token:$token
```

### 0.2 common response
#### response params
Params | Type | Desc
:---: | :---: | :---:
code | number | status code
msg | string | status description

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

## 1. Login登录
### 1.1 login登录
#### request url
```text
/admin/login
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
username | string | Y | user unique sign
password | string | Y | length:8-16

#### response params
Params | Type | Desc
:---: | :---: | :---:
token | string | auth token
roles | array | user's role

#### error code
Code | Msg|
:---: | :---: 
2000 | missing params: username or password
1000 | username or password error
1001 | user's role info error

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": {
        "token": "MiAxNTQ2NzY5MDcw",
        "roles": [
            "ROLE_ADMIN",
            "ROLE_DBA"
        ]
    }
}
```

## 2. Topic 主题
### 2.1 Topic info 标签信息
#### request url
```text
/admin/topic
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
topicType | number | Y | 0 or 1
page | number | N | default: 1
size | number | N | default: 20, max:20

#### response params
Params | Type | Desc
:---: | :---: | :---:
id | number | sign unique id
name | string | sign name

#### error code
Code | Msg|
:---: | :---: 
2002 | missing params: topic type
3000 | illegal argument: topic type

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": [
        {
            "id": 1,
            "name": "SpringBoot"
        },
        {
            "id": 2,
            "name": "SpringCloud"
        }
    ]
}
```

### 2.2 Add Topic 添加主题
#### request url
```text
/admin/topic/add
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicName | string(16) | topic name
topicType | number | 0 or 1

#### error code
Code | Msg|
:---: | :---: 
2001 | missing params: topic name
2002 | missing params: topic type
3000 | illegal argument: topic type
1002 | topic name exists already
4000 | table insert fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

## 3. Post 文章
### 2.1 Post info 文章信息
#### request url
```text
/admin/post
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
page | number | N | default: 1
size | number | N | default: 20, max:20

#### response params
Params | Type | Desc
:---: | :---: | :---:
nickname | string | author nickname
postId | number | post unique id
postTime | string | post publish time
status | string | post status
title | string | post title
keywords | string | post keywords
description | string | post description
isCommentClose | number | post comment status: open or close
isPrivate | number | post property: open or private
isCopy | number | post property: original or copy
originalLink | string | original link which reprints
isTop | number | post property: top of the post list or not
pv | number | post property: page view
commentCount | number | post property: comment count
likeCount | number | post property: like count

#### error code
NULL

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": [
        {
            "nickname": "fendoudebb",
            "postId": 19,
            "postTime": "2018-09-22 14:28:09",
            "status": 1,
            "title": "上传文件出现413错误(Request Entity Too Large)",
            "keywords": "Nginx,上传文件限制",
            "description": "Nginx上传文件限制大小",
            "isCommentClose": 0,
            "isPrivate": 0,
            "isCopy": 0,
            "originalLink": "",
            "isTop": 0,
            "pv": 0,
            "commentCount": 0,
            "likeCount": 0
        }
    ]
}
```
