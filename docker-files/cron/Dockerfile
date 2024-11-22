FROM php:8.2-fpm-alpine

WORKDIR /var/www/shaqexpressinvoice
# Install dependencies
RUN apk update && apk add --no-cache \
    curl \
    nano \
    sqlite-dev \
    sqlite-libs \
    autoconf \
    make \
    g++ \
    gcc

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

RUN pecl install redis && docker-php-ext-enable redis

# Create a cron job file
RUN touch /var/log/cron.log
COPY docker-files/cron/config/crontab /etc/crontabs/root

# Start the cron service
CMD ["crond", "-f"]
