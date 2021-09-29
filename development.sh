#!/bin/bash
#set -ex

COMMAND=$1
case ${COMMAND} in
    "docker-build")
        docker-compose down
        docker-compose build --pull
        docker-compose up -d
        ;;
    "composer-install")
        docker-compose run --rm -T composer composer install
        ;;
    "composer-update")
        docker-compose run --rm -T composer composer update "${@:2}"
        ;;
    "composer-clean")
        rm -Rf ./vendor ./composer.lock
        ;;
    "composer-require")
        docker-compose run --rm -T composer composer req "${@:2}"
        ;;
    "composer-remove")
        docker-compose run --rm -T composer composer rem "${@:2}"
        ;;
    "style-check")
        docker-compose run --rm -T composer /root/.composer/vendor/bin/phpcs --standard=PSR1,PSR12 "${@:2}"
        ;;
    "style-fix")
        docker-compose run --rm -T composer /root/.composer/vendor/bin/phpcbf --standard=PSR1,PSR12 "${@:2}"
        ;;
    "code-check")
        docker-compose run --rm -T phpunit vendor/bin/phpstan analyse src tests --level max
        ;;
esac
