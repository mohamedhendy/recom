FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG uid
ARG user

ARG DEBIAN_FRONTEND=noninteractive

# Install php extensions
RUN apt-get update && apt-get install -y libpq-dev libzip-dev && docker-php-ext-install pdo pdo_pgsql zip

# Install script dependencies
RUN apt-get install -yqq gnupg

# Clear out the local repository of retrieved package files
RUN apt-get clean && rm -r /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY ./docker/memory-limit-php.ini /usr/local/etc/php/conf.d/memory-limit-php.ini

# Add a user with id of host system so files are writable
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Change current user
USER $user

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
