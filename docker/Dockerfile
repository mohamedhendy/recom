# docker login gitlab-registry.wittkopf.eu
# docker build -t gitlab-registry.wittkopf.eu/olly/recom-inventory-system .
# docker push gitlab-registry.wittkopf.eu/olly/recom-inventory-system

# Set the base image
FROM php:7.4-fpm

# Update packages
RUN apt-get -yqq update

# Install php extensions
RUN apt-get install -yqq libpq-dev libcurl4-gnutls-dev libicu-dev zlib1g-dev libpng-dev libxml2-dev libzip-dev libbz2-dev exiftool
RUN docker-php-ext-install pdo_pgsql curl json intl gd xml zip bz2 opcache exif

# Install script dependencies
RUN apt-get install -yqq gnupg zip unzip

# Upgrade to Node 14
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -

# Install Node
RUN apt-get install -yqq nodejs

# Install Composer
RUN apt-get install -yqq curl
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install & enable Xdebug for code coverage reports
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Install for dusk
RUN apt-get install -yqq wget

# Set up the Chrome PPA
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list

# Update the package list and install chrome
RUN apt-get update -y
RUN apt-get install -y google-chrome-stable

# Set up Chromedriver Environment variables
ENV CHROMEDRIVER_DIR /chromedriver
RUN mkdir $CHROMEDRIVER_DIR
RUN export CHROMEDRIVER_VERSION=$(curl https://chromedriver.storage.googleapis.com/LATEST_RELEASE) && \
    wget -q --continue -P $CHROMEDRIVER_DIR "https://chromedriver.storage.googleapis.com/${CHROMEDRIVER_VERSION}/chromedriver_linux64.zip"
RUN unzip $CHROMEDRIVER_DIR/chromedriver* -d $CHROMEDRIVER_DIR


# Install client to import db structure
RUN apt-get install -yqq postgresql-client

# Install for deployment
RUN apt-get install -yqq openssh-client

# Install Laravel Envoy
RUN composer global require "laravel/envoy"

# Clear out the local repository of retrieved package files
RUN apt-get clean && rm -r /var/lib/apt/lists/*
