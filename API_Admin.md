## 0. Common
### 0.1 Headers  
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
    "code": 0,
    "msg": "request success"
}
```

## 1.Login
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

#### example
```json
{
    "code": 0,
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

