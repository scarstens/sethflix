#!/usr/bin/env bash

# Define the working base path.
baseDir=/var/www/sethflix.com/htdocs/

# Install dependencies and create the autoload file.
# First the lib/ dir for application wide use.
location=lib
fullPath=${baseDir}${location}

composer install -d ${fullPath}
composer dump-autoload -d ${fullPath}

## Now in the v2 dir.
#location=v2
#fullPath=${baseDir}${location}
#
#composer install -d ${fullPath}
#composer dump-autoload -d ${fullPath}
#
## Now in the Newsletter app dir.
#location=newsletter_app
#fullPath=${baseDir}${location}
#
#composer install -d ${fullPath}
#composer dump-autoload -d ${fullPath}
