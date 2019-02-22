#!/usr/bin/env bash

echo "Generating certificate..."
mkdir /etc/nginx/ssl
# Create entry params and generate cert.
printf 'US\nNew York\nNew York\nFanSided\nFanSided\napi.fansided.com\ntechteam@fansided.com\n' |
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt

