FROM php:8.1.0-fpm
LABEL maintainer="Josoé Schmidt Braga"

RUN apt-get update && apt-get install -y supervisor

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip

RUN mkdir -p /var/log/supervisor

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./builds/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 6001

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]