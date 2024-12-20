# Dockerfile
# Build stage
FROM composer:2 as composer
WORKDIR /app
COPY src/composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Production stage
FROM php:8.2-apache
WORKDIR /var/www/html

# Install required PHP extensions, SQLite, and Apache utilities
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    apache2-utils \
    curl \
    && docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite auth_basic authn_file \
    && rm -rf /var/lib/apt/lists/*

# Apache configuration
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copy website files
COPY src/ /var/www/html/
COPY --from=composer /app/vendor/ /var/www/html/vendor/

# Create directories and set permissions
RUN mkdir -p /var/www/html/database /var/www/html/admin \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/database

# Create a healthcheck script
COPY --chmod=755 docker-healthcheck.sh /usr/local/bin/
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD ["docker-healthcheck.sh"]

# Create admin credentials on container start
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

# Copy website files
COPY src/ /var/www/html/
COPY --from=composer /app/vendor/ /var/www/html/vendor/

# Create SQLite database directory with proper permissions
RUN mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/database
