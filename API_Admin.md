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

## 1. Login
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
1000 | username or password error
1001 | username or password is empty 
1002 | user's role info error

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

## 2. Tag Info标签信息
#### request params
Params | Type | Require | Desc
:---: | :---: | :---: | :---:
page | number | N | default: 1
size | number | N | default: 20, max:20

#### response params
Params | Type | Desc
:---: | :---: | :---:
tid | string(32) | sign unique id
name | string | sign name

#### error code
Code | Msg|
:---: | :---: 
1003 | tag is empty

#### example
```json
{
    "code": 200,
    "msg": "request success",
    "data": [
        {
            "tid": "4724268cca8fcc955785df24c4ad1db9",
            "name": "SpringBoot"
        },
        {
            "tid": "01f4c24e3b3df5714b549c94e6dda083",
            "name": "SpringCloud"
        }
    ]
}
```
