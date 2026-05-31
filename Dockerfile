FROM php:8.2-apache

# Installation des dépendances de base requises
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev zlib1g-dev unzip mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Configuration d'Apache pour le routage de l'index.php
RUN echo '<VirtualHost *:80>\n    DocumentRoot /var/www/html\n    <Directory /var/www/html>\n        Options Indexes FollowSymLinks\n        AllowOverride None\n        Require all granted\n        FallbackResource /index.php\n    </Directory>\n</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
