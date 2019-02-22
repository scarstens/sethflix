#!/usr/bin/env bash
echo 'BOOT: Getting permissions from aws-ssh server'
dbpw="$(ssh -oStrictHostKeyChecking=no -i /home/vagrant/.ssh/id_rsa developer@aws-ssh.fansided.com "cat /var/public-group/db1.pw")"

if [ ! -z "$dbpw" ]; then
	echo "Authorized to connect to production mysql, building tunnel"
    ssh -L 3307:fansidedapi-1.cluster-c6kceoi701kr.us-east-1.rds.amazonaws.com:3306 -N developer@aws-ssh.fansided.com -i /home/vagrant/.ssh/id_rsa &> /tmp/sshtunnel.log &
    if [ $? -eq 0 ]; then
        echo "successfully created ssh tunnel."
        ps aux | grep 3307
    else
        echo "error creating ssh tunnel."
        ps aux | grep 3307
    fi
else
	>&2 echo "Failed to authorize your accout, ~/.ssh/id_rsa is missing or uses a passkey and cannot be used to authenticate. Contact techteam@fansided.com or correct your id_rsa file."
	exit 0;
fi
