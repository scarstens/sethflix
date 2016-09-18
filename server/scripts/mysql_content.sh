#!/usr/bin/env bash
echo 'CONTETENT: Getting permissions from aws-ssh server'
dbpw="$(ssh -oStrictHostKeyChecking=no -i /home/vagrant/.ssh/id_rsa developer@aws-ssh.fansided.com "cat /var/public-group/db1.pw")"

if [ ! -z "$dbpw" ]; then
	echo "Re-authorized to connect to production mysql"
else
	>&2 echo "Failed to authorize your accout, ~/.ssh/id_rsa is missing or uses a passkey and cannot be used to authenticate. Contact techteam@fansided.com or correct your id_rsa file."
	exit 0;
fi

echo 'Pulling down some production data, excluding any tables with api_ prefix...'
touch ~/mysql_production_content_import_$(date +"%F").sql

unset dbtables
while read -a line
do
    dbtables+=("$line")
done < <(mysql -h 127.0.0.1 -P 3307 -ufansided_read -p$dbpw -se "SHOW TABLES IN fansided_api;")

n=0
for i in "${dbtables[@]}"
do
    if ! [[ $i == api* ]] ; then
        mysqldump --databases fansided_api --tables $i --where="1 ORDER BY (1) DESC limit 1000" -h 127.0.0.1 -P 3307 -ufansided_read -p$dbpw >> ~/mysql_production_content_import_$(date +"%F").sql
        n=$(($n+1))
        echo "  table[$n] $i downloaded"
    fi
done
echo 'Created content database sql files in root users home folder'

mysql fansided_api < ~/mysql_production_content_import_$(date +"%F").sql
echo "Executed SQL file at ~/mysql_production_content_import_$(date +"%F").sql"

echo "Example content"
mysql -e "SELECT post_key, post_title FROM fansided_api.posts_recent LIMIT 3";
dbpw=""
