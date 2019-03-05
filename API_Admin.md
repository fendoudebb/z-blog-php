[TOC]

## Table of Contents 目录
- [Common通用](#Common通用)
    - [Headers请求头](#Headers请求头)
    - [Response返回信息](#Response返回信息)
- [LoginAndLogout登录与退出](#LoginAndLogout登录与退出)
    - [Login登录](#Login登录)
    - [Logout退出](#Logout退出)
- [Post文章](#Post文章)
    - [PostInfo文章信息](#PostInfo文章信息)
    - [PostTopic文章主题](#PostTopic文章主题)
    - [PostTopicAdd删除文章主题](#PostTopicAdd添加文章主题)
    - [PostTopicDelete删除文章主题](#PostTopicDelete删除文章主题)
    - [PostComment文章评论](#PostComment文章评论)
    - [PostCommentSwitch切换文章评论状态](#PostCommentSwitch切换文章评论状态)

## Common通用
### Headers请求头
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

### Response返回信息
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

## LoginAndLogout登录与退出
### Login登录
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
Code | Msg
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

### Logout退出
#### request url
```text
/admin/logout
```

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

## Post文章
### PostInfo文章信息
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
totalCount | number | post total count
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
    "data": {
        "totalCount": 4460022,
        "post": [
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
}
```

### PostTopicAdd添加文章主题
#### request url
```text
/admin/post/topic/add
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
postId | string | Y | post id
topic | string | Y | topic

#### error code
Code | Msg
:---: | :---: 
2003 | missing params: post id
2004 | missing params: topic
1006 | post topic already exists
1007 | topic id does not exist
1008 | post id does not exist
1009 | over post topic count
4000 | table insert fail
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

### PostTopicDelete删除文章主题
#### request url
```text
/admin/post/topic/delete
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
postId | number | Y | post id
topicId | number | Y | topic id

#### error code
Code | Msg
:---: | :---: 
2003 | missing params: post id
2004 | missing params: topic id
3000 | illegal argument: post id
3002 | illegal argument: topic id
1004 | post topic does not exist
1005 | post topic has been deleted
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```

### PostComment文章评论
#### request url
```text
/admin/post/comment
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
postId | number | Y | post id
page | number | N | default: 1
size | number | N | default: 20, max:20

#### response params
Params | Type | Desc
:---: | :---: | :---:
currentPage | number | current page
pageSize | number | page size
totalCount | number | comment total count
totalPage | number | comment total page
commentId | number | comment id
isDelete | number | whether comment was deleted
parentId | number | comment parent id
postDate | string | comment post date
likeCount | number | comment like count
author | string | comment author
authorEmail | string | comment author email
authorIp | string | comment author ip
authorUserAgent | string | comment author user agent

#### error code
Code | Msg
:---: | :---: 
2003 | missing params: post id
3000 | illegal argument: post id

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": {
        "currentPage": 1,
        "pageSize": "20",
        "totalCount": 1003,
        "totalPage": 51,
        "comment": [
            {
                "commentId": 98005,
                "isDelete": 0,
                "parentId": 0,
                "postDate": "2019-01-23 14:54:43",
                "likeCount": 0,
                "author": "张三8001",
                "authorEmail": "",
                "authorIp": "",
                "authorUserAgent": ""
            }
        ]
    }
}
```

### PostCommentSwitch切换文章评论状态
#### request url
```text
/admin/post/comment/switch
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
postId | number | Y | post id

#### error code
Code | Msg
:---: | :---: 
2003 | missing params: post id
3000 | illegal argument: post id
1008 | post id does not exist
4001 | table update fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```
