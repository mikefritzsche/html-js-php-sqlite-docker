#!/bin/bash
set -e

# Create/update admin credentials
htpasswd -cb /var/www/html/admin/.htpasswd admin adminpassword
chown www-data:www-data /var/www/html/admin/.htpasswd
chmod 644 /var/www/html/admin/.htpasswd

# Create and set permissions for database directory
mkdir -p /var/www/html/database
touch /var/www/html/database/website.db
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database
chmod 664 /var/www/html/database/website.db

# Execute CMD
exec "$@"
