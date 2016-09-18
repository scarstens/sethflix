#!/usr/bin/env bash
echo "Fixing ownership.."
sudo chown -R sethcarstens:www-data /var/www/
echo "Fixing directory permissions..."
sudo find . -type d -name \* -exec chmod 775 {} \;
echo "Fixing file permissions..."
find . -type f -exec chmod 664 {} \;
echo "Done!"