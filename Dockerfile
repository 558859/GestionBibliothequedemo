FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies and Microsoft ODBC driver, then build PHP sqlsrv extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates gnupg2 wget curl build-essential \
        unixodbc-dev libssl-dev libxml2-dev libzip-dev zlib1g-dev \
    && mkdir -p /usr/share/keyrings \
    && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18 \
    && pecl channel-update pecl.php.net \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/* /tmp/pear /var/tmp/pear \
    && rm -f /etc/apt/sources.list.d/mssql-release.list /usr/share/keyrings/microsoft-prod.gpg

# Allow .htaccess overrides and add a FallbackResource pointing to index.php
RUN sed -ri 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf || true \
    && printf '\n<Directory /var/www/html>\n    FallbackResource /index.php\n</Directory>\n' >> /etc/apache2/sites-available/000-default.conf

# Copy application code
COPY . /var/www/html/

# Set ownership and permissions for web files
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80

CMD ["apache2-foreground"]
