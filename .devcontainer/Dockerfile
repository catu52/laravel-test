# PHP Version
FROM php:8.2-apache

ARG NODE_VERSION=18

RUN apt-get update -yqq \
    && apt-get install -yqq \
    ca-certificates \
    git \
    gcc \
    make \
    wget \
    mc \
    curl \
    libmcrypt-dev \
    libxml2-dev \
    libxslt-dev \
    libldb-dev \
    libldap2-dev \
    libpng-dev \
    libzip-dev \
    zip \
    vim

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

## Install php extension
RUN apt-get update -yqq \
    && apt-get install -yqq
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-install opcache
RUN docker-php-ext-install soap 
RUN docker-php-ext-install intl
RUN docker-php-ext-install xsl
RUN docker-php-ext-install ldap
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN apt-get install pkg-config

## Install composer globally
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN echo "Install composer globally"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install NVM (Node Version Manager)
RUN export NVM_DIR="$HOME/.nvm" && \
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash && \
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \
    nvm install ${NODE_VERSION} && \
    nvm alias default ${NODE_VERSION} && \
    nvm use default

# User permissions
RUN usermod -aG www-data www-data

# Add custom Directory configuration
# RUN echo '<Directory "/var/www/html/public">' >> /etc/apache2/apache2.conf && \
#     echo '    Options FollowSymLinks' >> /etc/apache2/apache2.conf && \
#     echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
#     echo '    Order allow,deny' >> /etc/apache2/apache2.conf && \
#     echo '    Allow from all' >> /etc/apache2/apache2.conf && \
#     echo '</Directory>' >> /etc/apache2/apache2.conf


# Enable mod_rewrite
RUN a2enmod rewrite

# Enable headers module
RUN a2enmod headers


# Set working directory
WORKDIR /var/www/html