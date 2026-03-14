#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
    # The first time volumes are mounted, the project needs to be recreated
    if [ "$APP_ENV" == 'int' ]; then
        echo 'CREATE BASIC APP FOLDERS';
        mkdir -p var/cache var/log var/session && chown -R www-data var
    elif [ "$APP_ENV" == 'prod' ]; then
        echo 'CREATE BASIC APP FOLDERS';
        mkdir -p var/cache var/log var/session && chown -R www-data var
    elif [ "$APP_ENV" != 'prod' ]; then
        # Always try to reinstall deps when not in prod
        echo 'CREATE BASIC APP FOLDERS';
        composer install --prefer-dist --no-progress --no-interaction
    fi
fi

exec docker-php-entrypoint "$@"
