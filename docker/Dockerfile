FROM php:7.3

RUN apt-get update

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-enable opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN  apt-get install libzip-dev -y \
    && docker-php-ext-install zip

RUN apt-get install unzip

RUN cd ~ \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
# composer cache persmission fix
RUN chmod a+w /var/www

COPY php.ini /usr/local/etc/php/

WORKDIR /var/src/
