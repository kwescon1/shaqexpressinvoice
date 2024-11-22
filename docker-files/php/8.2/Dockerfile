# Use official PHP 8.3 FPM image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/shaqexpressinvoice

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libpng-dev \
    libreadline-dev \
    libonig-dev \
    sudo \
    unzip \
    zip \
    nano \
    sqlite3 \
    libsqlite3-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install exif pcntl bcmath gd pdo pdo_mysql pdo_sqlite sockets

# Install Redis extensions
RUN pecl install redis && docker-php-ext-enable redis

# Install Xdebug extension
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug for code coverage
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Add user shaqexpressinvoice, add group shaqexpressinvoice
RUN useradd -rm -d /home/shaqexpressinvoice -s /bin/bash -g root -G sudo,www-data -u 1000 shaqexpressinvoice
RUN groupadd shaqexpressinvoice

# Get the latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set ownership of the working directory to shaqexpressinvoice
RUN chown -R shaqexpressinvoice:shaqexpressinvoice /var/www/shaqexpressinvoice

# Switch to the non-root user shaqexpressinvoice
USER shaqexpressinvoice
