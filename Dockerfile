FROM php:7.3.6-apache-stretch

RUN cat /etc/apt/preferences.d/no-debian-php
RUN rm /etc/apt/preferences.d/no-debian-php
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update

RUN apt -y install ca-certificates apt-transport-https wget gnupg2
RUN wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
RUN echo "deb https://packages.sury.org/php/ stretch main" | tee /etc/apt/sources.list.d/php.list
RUN cat /etc/apt/sources.list.d/php.list
RUN apt -y update

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN apt -y install zip unzip

RUN apt -y install php7.3-zip

RUN dpkg -s php7.3-zip |  grep Status

RUN cat /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini

RUN apt-get install -y zlib1g-dev libzip-dev

RUN docker-php-ext-install zip

RUN cat /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini

RUN composer global require "laravel/lumen-installer"

RUN cat /etc/apache2/apache2.conf

RUN ls /etc/apache2/sites-available

RUN cat /etc/apache2/sites-available/000-default.conf

RUN pwd

RUN ls /var/www/html

COPY . .

COPY apache/000-default.conf /etc/apache2/sites-available/

RUN cat /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN ls /var/www/html


RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev
RUN apt-get -y install libzip-dev
RUN wget -O- http://packages.couchbase.com/ubuntu/couchbase.key | apt-key add - 
RUN wget -O/etc/apt/sources.list.d/couchbase.list \
    http://packages.couchbase.com/ubuntu/couchbase-ubuntu1204.list
RUN apt-get update

RUN apt-get -y install lsb-release
RUN docker-php-ext-install zip
RUN composer self-update
RUN wget http://packages.couchbase.com/releases/couchbase-release/couchbase-release-1.0-6-amd64.deb
RUN dpkg -i couchbase-release-1.0-6-amd64.deb
#RUN rm -rf /var/lib/apt/lists/partial
RUN rm -rf /var/lib/apt/lists/*
RUN apt-get update -o Acquire::CompressionTypes::Order::=gz
RUN apt-get update
RUN apt-get install libcouchbase-dev build-essential zlib1g-dev
RUN pecl install couchbase
RUN echo "extension=couchbase.so" | tee /usr/local/etc/php/php.ini > /dev/null

RUN composer install -n --prefer-dist
RUN cp -fr ./.circleci/HybridRelations.php ./vendor/developermarshak/laravel-couchbase/src/Mpociot/Couchbase/Eloquent/HybridRelations.php
RUN cp -fr ./.circleci/Model.php ./vendor/developermarshak/laravel-couchbase/src/Mpociot/Couchbase/Eloquent/Model.php

ARG Google_Analytics_Service_Account=default_value
RUN echo $Google_Analytics_Service_Account > app/Services/service-account-credentials.json

ARG Google_Cloud_Storage_Service_Account=default_value
RUN echo $Google_Cloud_Storage_Service_Account > app/Services/image-upload-service-credentials.json

ARG db_connection=default_value
ENV DB_CONNECTION=$db_connection
ARG db_host=default_value
ENV DB_HOST=$db_host
ARG db_port=default_value
ENV DB_PORT=$db_port
ARG db_database=default_value
ENV DB_DATABASE=$db_database
ARG db_username=default_value
ENV DB_USERNAME=$db_username
ARG db_password=default_value
ENV DB_PASSWORD=$db_password
ARG cache_driver=default_value
ENV CACHE_DRIVER=$cache_driver
ARG queue_connection=default_value
ENV QUEUE_CONNECTION=$queue_connection
ARG jwt_secret=default_value
ENV JWT_SECRET=$jwt_secret
ARG frontend_url=default_value
ENV FRONTEND_URL=$frontend_url
ARG mj_apikey_public=default_value
ENV MJ_APIKEY_PUBLIC=$mj_apikey_public
ARG mj_api_email=default_value
ENV MJ_API_EMAIL=$mj_api_email
ARG mj_api_secret_key=default_value
ENV MJ_API_SECRET_KEY=$mj_api_secret_key

ARG twitter_api_key=default_value
ENV TWITTER_API_KEY=$twitter_api_key
ARG twitter_api_key_secret=default_value
ENV TWITTER_API_KEY_SECRET=$twitter_api_key_secret
ARG twitter_access_token=default_value
ENV TWITTER_ACCESS_TOKEN=$twitter_access_token
ARG twitter_access_token_secret=default_value
ENV TWITTER_ACCESS_TOKEN_SECRET=$twitter_access_token_secret
ARG fb_page_id=default_value
ENV FB_PAGE_ID=$fb_page_id
ARG fb_app_id=default_value
ENV FB_APP_ID=$fb_app_id
ARG fb_app_secret=default_value
ENV FB_APP_SECRET=$fb_app_secret
ARG fb_access_token=default_value
ENV FB_ACCESS_TOKEN=$fb_access_token
ARG yb_api_key=default_value
ENV YB_API_KEY=$yb_api_key
ARG yb_channel_id=default_value
ENV YB_CHANNEL_ID=$yb_channel_id
ARG Google_Analytics_Profile_ID=default_value
ENV GOOGLE_ANALYTICS_PROFILE_ID=$Google_Analytics_Profile_ID
ARG Google_Storage_Bucket=default_value
ENV GOOGLE_STORAGE_BUCKET=$Google_Storage_Bucket


RUN composer update --no-scripts

#RUN ./vendor/bin/phpunit --debug
