##
# JS Building Stage
##

FROM node:14 as node_builder

RUN apt-get update && \
    apt-get install -y git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    rm -f $HOME/.npmrc

WORKDIR /node-app

COPY --chown=1000:1000 . .

RUN npm install && \
    npm run production

##
# PHP Building Stage
##

FROM composer:1.10.7 as php_builder

WORKDIR /php-app

COPY --chown=1000:1000 --from=node_builder /node-app .

RUN rm -f bootstrap/cache/* && \
    composer install

##
# Final Stage
##

FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update &&\
    apt-get install -y \
        build-essential \
        curl \
        jpegoptim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libpq-dev\
        libzip-dev \
        locales \
        optipng \
        pngquant \
        zlib1g-dev && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install mbstring zip exif pcntl pgsql pdo pdo_pgsql && \
    pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Copy files from built source
COPY --chown=1000:1000 --from=php_builder /php-app .

COPY --chown=1000:1000 docker-custom/app.entrypoint.sh /app.entrypoint.sh
# Change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Setup PHP configs

COPY --chown=1000:1000 docker-custom/config/php/local.ini docker-custom/config/php/php.ini /usr/local/etc/php/
COPY --chown=1000:1000 docker-custom/config/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:1.10.7 /usr/bin/composer /usr/local/bin/composer

# Expose port 9000 and start php-fpm server
EXPOSE 9000

#ENTRYPOINT ["/app.entrypoint.sh"]
CMD ["php-fpm"]