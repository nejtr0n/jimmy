FROM php:8-fpm
ARG TZ=Europe/Moscow
ENV TZ ${TZ}
ENV DEBIAN_FRONTEND noninteractive
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer
# Install Phpunit
RUN curl -o /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-9.phar && chmod +x /usr/local/bin/phpunit
# Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
# Locale
RUN apt-get update && apt-get install -y --no-install-recommends locales && \
    dpkg-reconfigure -f noninteractive tzdata && \
    sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen && \
    echo 'LANG="ru_RU.UTF-8"'>/etc/default/locale && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=ru_RU.UTF-8
ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU:ru
ENV LC_ALL ru_RU.UTF-8
# PHP
RUN apt-get install -y sudo libcurl4-gnutls-dev curl \
libmcrypt-dev libzip-dev zip unzip postgresql-server-dev-all libfreetype6-dev libonig-dev \
libjpeg62-turbo-dev libpng-dev libpcre3-dev librabbitmq-dev supervisor libevent-dev libssl-dev libsqlite3-dev libxml2-dev libmagickwand-dev \
    && docker-php-source extract \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-source delete \
    && docker-php-ext-install iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mbstring tokenizer gd exif opcache bcmath sockets pdo_mysql pdo_pgsql pdo_sqlite soap intl zip

#RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.idekey=jimmy" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN usermod  -u 1000 www-data
RUN groupmod -g 1000 www-data