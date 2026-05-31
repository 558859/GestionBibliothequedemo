FROM php:8.2-apache

# Installation des dépendances système et des outils Microsoft SQL Server
RUN apt-get update && apt-get install -y \
    gnupg2 \
    curl \
    apt-transport-https \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.dist/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP pdo_sqlsrv et sqlsrv
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Activation du module de réécriture d'Apache (mod_rewrite)
RUN a2enmod rewrite

# Configuration d'Apache pour forcer la lecture du .htaccess sur Render
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copie des fichiers du projet dans le dossier du serveur web
COPY . /var/www/html/

# Ajustement des permissioFROM php:8.2-apache

# 1. Installation des dépendances et du pilote SQL Server de Microsoft
RUN apt-get update && apt-get install -y \
    gnupg2 \
    curl \
    apt-transport-https \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.dist/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Installation et activation des extensions PHP pour SQL Server
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# 3. Forcer Apache à rediriger TOUTES les pages vers index.php (Routage automatique)
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride None\n\
        Require all granted\n\
        FallbackResource /index.php\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 4. Copie des fichiers du projet
COPY . /var/www/html/

# 5. Droits d'accès Linux
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80ns
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80