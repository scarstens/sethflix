# Use the latest Ubuntu base image
FROM ubuntu:14.04
MAINTAINER Seth Carstens <seth.carstens@gmail.com.com>

ENV TERM=xterm

RUN DEBIAN_FRONTEND=noninteractive apt-get -y update && apt-get -y install software-properties-common

# DOWNLOAD SHELL ESSENTIALS
RUN DEBIAN_FRONTEND=noninteractive apt-get install --yes \
    git \
    imagemagick \
    zip \
    unzip \
    ngrep \
    make \
    puppet-common \
    libyaml-dev

# Install Node
RUN apt-add-repository -y ppa:chris-lea/node.js
RUN DEBIAN_FRONTEND=noninteractive apt-get install --yes \
  nodejs

# Apply the PHP signing key
RUN apt-key adv --quiet --keyserver "hkp://keyserver.ubuntu.com:80" --recv-key E5267A6C 2>&1 | grep "gpg:"
RUN apt-key export E5267A6C | apt-key add -

# Install Nginx+PHP7+membached
RUN apt-add-repository ppa:ondrej/php
RUN add-apt-repository ppa:nginx/stable
RUN DEBIAN_FRONTEND=noninteractive apt-get -y update && apt-get install --yes \
  nginx \
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
  memcached

RUN service nginx start
RUN service php7.0-fpm start

# Reference: http://stackoverflow.com/questions/18861300/how-to-run-nginx-within-docker-container-without-halting
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf

# Set up composer variables
ENV COMPOSER_BINARY=/usr/local/bin/composer \
    COMPOSER_HOME=/usr/local/composer
ENV PATH $PATH:$COMPOSER_HOME

# Install composer system-wide
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar $COMPOSER_BINARY && \
    chmod +x $COMPOSER_BINARY && \
    chmod a+rw $COMPOSER_HOME

# start httpd
VOLUME /var/www/sethflix.com/htdocs

# Backup the default configurations
#RUN cp /etc/php5/fpm/php.ini /etc/php5/fpm/php.ini.original.bak
RUN mv /etc/nginx/sites-available/default /etc/nginx/sites-available/default.original

COPY id_rsa /root/.ssh/id_rsa
COPY server/scripts/* /var/www/provision/
COPY . /var/www/sethflix.com/htdocs/
RUN chmod +r /var/www/provision/autoload.sh && bash /var/www/provision/autoload.sh

EXPOSE 80
EXPOSE 443
EXPOSE 22
