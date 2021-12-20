#/bin/bash
mongodb=$1

mongo <<EOF
    use $1
    db.createUser(
    {
        user: "$1",
        pwd: "$1",
        roles: [
            { role: "readWrite", db: "$1" }
        ]
    }
)
    db.init.insertOne({});
EOF
