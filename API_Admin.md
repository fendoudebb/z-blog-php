## 0. Common
### 0.1 Headers  
#### Http Method
POST
#### Content-Type
```http request
Content-Type:application/json;charset=utf‐8
```

#### token(Except Login API)
```http request
token:[$token]
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
1004 | topic name exists already
4000 | table insert fail

#### example
```json
{
    "code": 200,
    "msg": "request success"
}
```
