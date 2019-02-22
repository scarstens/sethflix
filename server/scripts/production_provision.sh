#!/usr/bin/env bash
#
# Provision live server.
#

# Add php7
apt-get install -y language-pack-en-base

# Node time.
echo "Add Nodejs to package list..."
curl -sL https://deb.nodesource.com/setup_4.x — Node.js v4 LTS "Argon" | sudo -E bash -

LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
apt-get update

# Apply the PHP signing key
apt-key adv --quiet --keyserver "hkp://keyserver.ubuntu.com:80" --recv-key E5267A6C 2>&1 | grep "gpg:"
apt-key export E5267A6C | apt-key add -
apt-get update

apt-get install -y \
  build-essential \
  git \
  htop \
  php7.0-fpm \
  php7.0-cli \
  php7.0-common \
  php7.0-dev \
  php-memcache \
  php-imagick \
  php7.0-mbstring \
  php7.0-mcrypt \
  php7.0-mysql \
  php7.0-imap \
  php7.0-curl \
  php-pear \
  php7.0-gd \
  memcached \
  imagemagick \
  zip \
  unzip \
  ngrep \
  make \
  nodejs \
  libyaml-dev

apt-get clean

add-apt-repository ppa:nginx/stable
apt-get update
apt-get install -y nginx
apt-get clean

# Install yaml stuff. Note the required libyaml-dev above.
pecl install yaml-beta
sh -c "echo extension=yaml.so > /etc/php/7.0/mods-available/yaml.ini"
ln -sf /etc/php/7.0/mods-available/yaml.ini /etc/php/7.0/fpm/conf.d/20-yaml.ini
ln -sf /etc/php/7.0/mods-available/yaml.ini /etc/php/7.0/cli/conf.d/20-yaml.ini

# Get thea AMP validator package.
npm install -g amphtml-validator

# do this after git pull of api files. http://superuser.com/questions/19318/how-can-i-give-write-access-of-a-folder-to-all-users-in-linux