#!/bin/bash
source {{ APP_DEPLOY_FOLDER }}/.env

#for local mysql only
if mysql -e "show databases;" | cut -d \| -f 1 | grep -qw $1; then
        echo "Database $1 already exists"
else

        mysql -e "create database $1"
        mysqldump $DB_DATABASE | mysql $1
fi