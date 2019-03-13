
// ----------------------------
// Collection structure for ip_pool
// ----------------------------
db.getCollection("ip_pool").drop();
db.createCollection("ip_pool");

// ----------------------------
// Collection structure for page_view_record
// ----------------------------
db.getCollection("page_view_record").drop();
db.createCollection("page_view_record");

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
                }
            },
            required: [
                "postStatus",
                "commentStatus"
            ]
        }
    },
    validationLevel: "strict",
    validationAction: "error"
});
db.getCollection("post").createIndex({
    postId: "",
    postStatus: "",
    postTime: ""
}, {
    name: "post:id-status-time",
    background: true
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
    name: "uk_username",
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
