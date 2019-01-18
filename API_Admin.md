[TOC]

## Table of Contents 目录
- [Common通用](#Common通用)
    - [headers请求头](#headers请求头)
    - [response返回信息](#response返回信息)
- [Login登录](#Login登录)
    - [login登录](#login登录)
- [Topic主题](#Topic主题)
    - [topic info标签信息](#topic info标签信息)
    - [add topic 添加主题](#add topic添加主题)
    - [modify topic sort修改主题排序](#modify topic sort修改主题排序)
    - [modify topic name修改主题名称](#modify topic name修改主题名称)
    - [modify topic parent修改主题父类](#modify topic parent修改主题父类)
- [Post文章](#Post文章)
    - [post info文章信息](#post info文章信息)
    - [post topic文章主题](#post topic文章主题)

## Common通用
### headers请求头
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

### response返回信息
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

## Login登录
### login登录
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

## Topic主题
### topic info标签信息
#### request url
```text
/admin/topic
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
topicParentId | number | Y | topic parent id
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
2002 | missing params: topic parent id
3001 | illegal argument: topic parent id

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

### add topic添加主题
#### request url
```text
/admin/topic/add
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicName | string(16) | topic name
topicParentId | number | topic parent id

#### error code
Code | Msg|
:---: | :---: 
2001 | missing params: topic name
2002 | missing params: topic parent id
3001 | illegal argument: topic parent id
1002 | topic name exists already
4000 | table insert fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

### modify topic sort修改主题排序
#### request url
```text
/admin/topic/modify/sort
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicId | number | topic id
topicSort | number | topic sort

#### error code
Code | Msg|
:---: | :---: 
2004 | missing params: topic id
2005 | missing params: topic sort
3002 | illegal argument: topic id
3003 | illegal argument: topic sort
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

### modify topic name修改主题名称
#### request url
```text
/admin/topic/modify/name
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicId | number | topic id
topicName | string | topic name

#### error code
Code | Msg|
:---: | :---: 
2004 | missing params: topic id
2001 | missing params: topic name
3002 | illegal argument: topic id
1002 | topic name exists already
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

### modify topic parent修改主题父类
#### request url
```text
/admin/topic/modify/parent
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicId | number | topic id
topicParentId | string | topic parent id

#### error code
Code | Msg|
:---: | :---: 
2004 | missing params: topic id
2002 | missing params: topic parent id
3002 | illegal argument: topic id
3001 | illegal argument: topic parent id
1003 | topic parent id not exist
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

## Post文章
### post info文章信息
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

### post topic文章主题
#### request url
```text
/admin/post/topic
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
postId | number | Y | post id

#### response params
Params | Type | Desc
:---: | :---: | :---:
topicName | string | topic name
isDelete | number | topic is delete

#### error code
Code | Msg|
:---: | :---: 
2003 | missing params: post id
3000 | illegal argument: post id

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": [
        {
            "topicName": "前端",
            "isDelete": 0
        },
        {
            "topicName": "后端",
            "isDelete": 1
        }
    ]
}
```
