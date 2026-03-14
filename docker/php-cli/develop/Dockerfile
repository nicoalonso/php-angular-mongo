ARG PHP_VERSION=8.3

### PHP
FROM php:${PHP_VERSION}-cli-alpine3.18

ENV APCU_VERSION 5.1.23
ENV MONGODB_VERSION 1.17.3
ENV REDIS_VERSION 6.0.2
ENV AMPQ_VERSION 2.1.2

RUN apk add --no-cache \
        libltdl \
        libmcrypt-dev \
        git \
		icu-libs \
		zlib \
		jq \
        zip \
        libzip-dev

RUN set -eux \
    && apk add --no-cache --virtual libssl1.0 \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
		icu-dev \
		zlib-dev \
		openssl-dev \
        curl \
        rabbitmq-c-dev \
	&& docker-php-ext-install -j$(nproc) \
	    gd \
		intl \
		zip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd \
	&& pecl install \
		apcu-${APCU_VERSION} \
        mongodb-${MONGODB_VERSION} \
        redis-${REDIS_VERSION} \
        amqp-${AMPQ_VERSION} \
	&& docker-php-ext-enable --ini-name 20-apcu.ini apcu \
	&& docker-php-ext-enable --ini-name 05-opcache.ini opcache \
    && docker-php-ext-enable mongodb.so \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable amqp \
	&& runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )" \
    && apk add --no-cache --virtual .api-phpexts-rundeps $runDeps \
	&& apk del .build-deps

RUN ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./conf.d/symfony.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY ./docker-entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint

WORKDIR /srv/app
ENTRYPOINT ["docker-app-entrypoint"]
CMD ["php"]

RUN mkdir -p var/cache var/log var/session && chown -R www-data var

ARG HOST_USER=developer
ARG HOST_USER_ID=1000
ARG HOST_GROUP=developer
ARG HOST_GROUP_ID=1000
ARG TIMEZONE=Europe/Madrid

# see https://stackoverflow.com/questions/49955097/how-do-i-add-a-user-when-im-using-alpine-as-a-base-image
RUN addgroup -g ${HOST_GROUP_ID} -S ${HOST_GROUP} && adduser -u ${HOST_USER_ID} -G ${HOST_GROUP} -s /bin/sh -D -g "" ${HOST_USER}

COPY --from=composer:2.7.2 /usr/bin/composer /usr/bin/composer

RUN chown -R ${HOST_USER}:${HOST_GROUP} /srv/app

# https://wiki.alpinelinux.org/wiki/Setting_the_timezone
RUN apk add tzdata \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && apk del tzdata \
    && date

# Some tools for development
# https://stackoverflow.com/questions/41332673/best-way-to-reduce-the-size-of-a-custom-docker-image
RUN apk update  \
    && apk add nano \
    && apk add --no-cache g++ make autoconf linux-headers \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && echo "xdebug.mode=develop,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -rf /tmp/* \
    && rm -rf /var/cache/apk/* \
    && mkdir -p /opt/phpstorm-coverage/ \
    && chmod 777 /opt/phpstorm-coverage/

USER ${HOST_USER}
