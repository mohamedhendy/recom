#/bin/bash
dropdb $1
createdb $1
psql $1 < /var/develop/backups/recom/db/dump.sql
php artisan migrate
