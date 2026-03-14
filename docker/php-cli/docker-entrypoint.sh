#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php "$@"
fi

if [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    if [ "$APP_ENV" == 'int' ]; then
        echo 'CREATE BASIC APP FOLDERS';
        mkdir -p var/cache var/log var/session && chown -R www-data var
    elif [ "$APP_ENV" == 'prod' ]; then
        echo 'CREATE BASIC APP FOLDERS';
        mkdir -p var/cache var/log var/session && chown -R www-data var
    elif [ "$APP_ENV" != 'prod' ]; then
        # Always try to reinstall deps when not in prod
        echo 'INSTALL APP VENDORS';
        composer install --prefer-dist --no-progress --no-suggest --no-interaction
    fi
fi

exec docker-php-entrypoint "$@"
