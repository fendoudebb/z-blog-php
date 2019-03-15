
// ----------------------------
// Collection structure for ip_pool
// ----------------------------
db.getCollection("ip_pool").drop();
db.createCollection("ip_pool");
db.getCollection("ip_pool").createIndex({
    ip: ""
}, {
    name: "uk_ip",
    background: true,
    unique: true
});

// ----------------------------
// Collection structure for page_view_record
// ----------------------------
db.getCollection("page_view_record").drop();
db.createCollection("page_view_record");
db.getCollection("page_view_record").createIndex({
    createTime: ""
}, {
    name: "createTime_"
});
db.getCollection("page_view_record").createIndex({
    url: "",
    createTime: ""
}, {
    name: "url__createTime_"
});

// ----------------------------
// Collection structure for post
// ----------------------------
db.getCollection("post").drop();
db.createCollection("post",{
    validator: {
        $jsonSchema: {
            properties: {
                postStatus: {
                    bsonType: "string",
                    enum: [
                        "AUDIT",
                        "ONLINE",
                        "OFFLINE",
                        "PRIVATE",
                        "DRAFT"
                    ]
                },
                commentStatus: {
                    bsonType: "string",
                    enum: [
                        "OPEN",
                        "CLOSE"
                    ]
                },
                postId: {
                    bsonType: "long"
                },
                postProp: {
                    bsonType: "string",
                    enum: [
                        "ORIGINAL",
                        "COPY"
                    ]
                },
                topics: {
                    bsonType: "array"
                },
                postLike: {
                    bsonType: "array"
                }
            },
            required: [
                "postStatus",
                "commentStatus",
                "postId",
                "postProp"
            ]
        }
    },
    validationLevel: "strict",
    validationAction: "error"
});
db.getCollection("post").createIndex({
    postId: "",
    postStatus: ""
}, {
    name: "postId__postStatus_",
    background: true
});
db.getCollection("post").createIndex({
    postId: ""
}, {
    name: "postId_",
    background: true,
    unique: true
});

// ----------------------------
// Collection structure for sys_user
// ----------------------------
db.getCollection("sys_user").drop();
db.createCollection("sys_user",{
    validator: {
        $jsonSchema: {
            properties: {
                username: {
                    bsonType: "string"
                },
                password: {
                    bsonType: "string"
                },
                roles: {
                    bsonType: "array",
                    enum: [
                        "ROLE_ADMIN",
                        "ROLE_NORMAL"
                    ]
                }
            },
            required: [
                "username",
                "password",
                "roles"
            ]
        }
    },
    validationLevel: "strict",
    validationAction: "error"
});
db.getCollection("sys_user").createIndex({
    username: ""
}, {
    name: "username_",
    background: true,
    unique: true
});

// ----------------------------
// Collection structure for system.profile
// ----------------------------
db.getCollection("system.profile").drop();
db.createCollection("system.profile",{
    capped: true,
    size: 1048576
});
