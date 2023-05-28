#Use an official PHP image as the base image
FROM php:8.1.0-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl zip gd \
    && rm -rf /var/lib/apt/lists/* 


RUN curl -sS https://getcomposer.org/installer | tee composer-setup.php \
    && php composer-setup.php && rm composer-setup.php* \ 
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer 
# && ln -s /usr/bin/php /usr/local/bin/php

RUN php -v

RUN mkdir /app

COPY ./code /app

WORKDIR /app

RUN mv /app/.env.prd /app/.env

RUN composer clearcache

RUN composer self-update

#RUN composer install -v
RUN composer install

#RUN chmod 777 -R ./storage/framework

RUN php artisan storage:link
RUN php artisan optimize:clear

RUN php artisan cache:clear
RUN chmod 777 ./public -R
RUN chmod 777 ./storage -R
RUN chmod 777 ./storage/logs/ -R
#RUN php artisan cache:clear
#RUN mkdir ./storage/app/public/arquivos

RUN chmod 777 ./public -R

# RUN chmod -R 777 ./framework/views
# RUN chmod -R 777 ./framework/sessions
# RUN chmod -R 777 ./framework/cache

######################################################

# Update the package repository cache
RUN apt-get update

# Install nodejs and npm
#RUN apt-get install -y nodejs npm

#RUN node -v
#RUN npm -v
#RUN npm i npm@latest -g

# Start the PHP-FPM process
CMD ["php-fpm","-F"]