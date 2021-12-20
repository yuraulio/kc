#!/bin/bash
source {{ APP_DEPLOY_FOLDER }}/.env
export PGPASSWORD=$DB_PASSWORD;

PG_CONN_PARAM="sslmode=require host=$DB_HOST port=$DB_PORT dbname=$DB_DATABASE"
PG_REVIEW_DB=$1

if psql -lqt "$PG_CONN_PARAM" --username=$DB_USERNAME | cut -d \| -f 1 | grep -qw $PG_REVIEW_DB; then
        echo "Database $PG_REVIEW_DB already exists"
else
        #CreateDB
        psql "$PG_CONN_PARAM" --username=$DB_USERNAME -c "create database $PG_REVIEW_DB;";
        #Clone Stage DB
        pg_dump "$PG_CONN_PARAM" --username=$DB_USERNAME | psql "sslmode=require host=$DB_HOST port=$DB_PORT dbname=$PG_REVIEW_DB" --username=$DB_USERNAME;
fi