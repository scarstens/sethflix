#!/usr/bin/env bash
#
# db_backup.sh
# 
# This script backups FS API database and moves into Rackspace for
# storage. It also cleans the Rackspace Object container for db stores
# older than a year.
# 
# Requires that db folder exists in /var/api_bak/

backup_dir=/var/api_backup/
now=$(date +"%m_%d_%Y")
filename=${backup_dir}fansided_api_$now.bak.gz

mysqldump -u fansided_api -ps9JHGA77AXi2 fansided_api | gzip -9 > $filename

dir=/var/www/api.fansided.com/htdocs/
rackspace=/var/www/api.fansided.com/htdocs/server/backup/rackspace/rackspace_utility.class.php

php -r "
  require('$rackspace');
  Rackspace_Utility::upload_file( '$filename' );
  Rackspace_Utility::clean_files();"

# Delete log files older than 1 year.
find $backup_dir -type f -mtime +365 -exec rm -rf {} \;
