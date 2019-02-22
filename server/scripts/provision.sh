#!/usr/bin/env bash
#
# provision.sh
#
# Creates PHP7 dev environment for use with latest version of PHPUnit.

# Node time.
echo "Add Nodejs to package list..."
curl -sL https://deb.nodesource.com/setup_4.x — Node.js v4 LTS "Argon" | sudo -E bash -

apt-add-repository ppa:ondrej/php
apt-get update

# Apply the PHP signing key
apt-key adv --quiet --keyserver "hkp://keyserver.ubuntu.com:80" --recv-key E5267A6C 2>&1 | grep "gpg:"
apt-key export E5267A6C | apt-key add -
apt-get -y update

apt-get install --yes \
  git \
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
  puppet-common \
  libyaml-dev \
  nodejs

 apt-get clean

# Install Composer if it is not yet available.
if [[ ! -n "$(composer --version --no-ansi | grep 'Composer version')" ]]; then
  echo "Installing Composer..."
  curl -sS https://getcomposer.org/installer | php
  chmod +x composer.phar
  mv composer.phar /usr/local/bin/composer
fi

# Install puppet from deb package and install modules if needed
puppet module install --module_repository https://forge.puppet.com/ puppetlabs-mysql

# Install xdebug
# Usage:
# Env vars are set on vagrant up. Config is below. Settings:
# PHP server:
#   name: api.fansided.dev
#   port: 80
#   Absolute path: /var/www/api.fansided.com/htdocs/
#
# PHP Debug:
#   IDE Key: PHPSTORM
#   host: 192.168.99.99
#   port: 9001
#
# Usage: Start listening for PHP Debug connections.
# CLI:
# usage is available via alias phpd="php -dxdebug.remote_host=10.0.2.2" @see .bash_aliases
# REST:
# Just make the calls and you're good to go.

if [[ -f /usr/lib/php/20151012/xdebug.so ]]; then
  echo "Xdebug already installed"
else
  echo "Installing Xdebug"
  # Download and extract Xdebug.
  curl -L -O --silent https://xdebug.org/files/xdebug-2.4.0.tgz
  tar -xf xdebug-2.4.0.tgz
  cd xdebug-2.4.0
  # Create a build environment for Xdebug based on our PHP configuration.
  phpize
  # Complete configuration of the Xdebug build.
  ./configure -q
  # Build the Xdebug module for use with PHP.
  make -s > /dev/null
  # Install the module.
  cp modules/xdebug.so /usr/lib/php/20151012/xdebug.so
  # Clean up.
  cd ..
  rm -rf xdebug-2.4.0*
  echo "Xdebug installed"
fi

echo "zend_extension=/usr/lib/php/20151012/xdebug.so
xdebug.remote_connect_back=1
xdebug.remote_autostart=1
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_port=9001
xdebug.max_nesting_level=300
xdebug.idekey=\"PHPSTORM\"" > /etc/php/7.0/mods-available/xdebug.ini

echo "export PHP_IDE_CONFIG=\"serverName=api.fansided.dev\";
export XDEBUG_CONFIG=\"idekey=PHPSTORM\";" >> /home/vagrant/.bashrc

ln -sf /etc/php/7.0/mods-available/xdebug.ini /etc/php/7.0/fpm/conf.d/20-xdebug.ini
ln -sf /etc/php/7.0/mods-available/xdebug.ini /etc/php/7.0/cli/conf.d/20-xdebug.ini

# Set up some goodies
cp /var/www/api.fansided.com/htdocs/server/conf/.inputrc /home/vagrant/.inputrc
cp /var/www/api.fansided.com/htdocs/server/conf/.bash_aliases /home/vagrant/.bash_aliases

# Install yaml stuff. Note the required libyaml-dev above.
pecl install yaml-beta
sh -c "echo extension=yaml.so > /etc/php/7.0/mods-available/yaml.ini"
ln -sf /etc/php/7.0/mods-available/yaml.ini /etc/php/7.0/fpm/conf.d/20-yaml.ini
ln -sf /etc/php/7.0/mods-available/yaml.ini /etc/php/7.0/cli/conf.d/20-yaml.ini

# Get thea AMP validator package.
npm install -g amphtml-validator
