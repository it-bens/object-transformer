FROM php:8.0-cli-alpine

# install dependencies
RUN set -ex; \
    apk add --no-cache --virtual \
    .build-deps \
    $PHPIZE_DEPS \
	bash \
	git

# install composer (needed for PHPUnit updates)
RUN set -ex; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install composer plugins
RUN set -ex; \
    composer global require "squizlabs/php_codesniffer=*"

VOLUME /var/www/html
WORKDIR /var/www/html
