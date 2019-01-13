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

## 2. Tag标签
### 2.1 Tag info 标签信息
#### request url
```text
/admin/tag
```

#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
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
1002 | tag is empty

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

### 2.2 Tag type 标签类别
#### request url
```text
/admin/type
```

#### response params
Params | Type | Desc
:---: | :---: | :---:
id | number | sign unique id
name | string | sign name

#### error code
Code | Msg|
:---: | :---: 
1003 | tag type is empty

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": [
        {
            "id": 1,
            "name": "前端"
        },
        {
            "id": 2,
            "name": "后端"
        }
    ]
}
```
