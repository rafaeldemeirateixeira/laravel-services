FROM php:7.3.6-fpm-alpine3.9
RUN apk add --no-cache openssl bash mysql-client nodejs npm alpine-sdk autoconf librdkafka-dev vim
RUN docker-php-ext-install pdo pdo_mysql bcmath
RUN pecl install rdkafka-3.0.3

RUN ln -s /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    echo "extension=rdkafka.so" >> /usr/local/etc/php/php.ini

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --update supervisor && rm  -rf /tmp/* /var/cache/apk/*
COPY ./supervisor/supervisord.conf /etc/
COPY ./supervisor/conf.d/*.conf /etc/supervisor/conf.d/
RUN mkdir /var/log/supervisor

EXPOSE 9000

COPY ./entrypoint.sh /tmp/entrypoint.sh
RUN ["chmod", "+x", "/tmp/entrypoint.sh"]

ENTRYPOINT ["/tmp/entrypoint.sh"]