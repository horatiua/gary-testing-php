FROM php:8.1-fpm-alpine as app

# Copy php.ini config file into container
#COPY ./php/conf.d/php.ini $PHP_INI_DIR/conf.d/
# Watch out because the $_ENV variable is not included by default in variables_order - use getenv() instead
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# RUN docker-php-ext-install pdo pdo_mysql

# Useful PHP extension installer image, copy binary into your container
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install php extensions
# exit on errors, exit on unset variables, print every command as it is executed
RUN set -eux; \
    install-php-extensions pdo pdo_mysql;

# allow super user - set this if you use Composer as a
# super user at all times like in docker containers
ENV COMPOSER_ALLOW_SUPERUSER=1

# obtain composer using multi-stage build
# https://docs.docker.com/build/building/multi-stage/
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# Here, we are copying only composer.json and composer.lock (instead of copying the entire source)
# right before doing composer install.
# This is enough to take advantage of docker cache and composer install will
# be executed only when composer.json or composer.lock have indeed changed!-
# https://medium.com/@softius/faster-docker-builds-with-composer-install-b4d2b15d0fff
COPY ./app/composer.* ./

# install
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

RUN pwd
RUN ls -l vendor/

# copy application files to the working directory
COPY ./app .

# run composer dump-autoload --optimize
RUN composer dump-autoload --optimize

# Dev image
# This stage is meant to be target-built into a separate image
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target
FROM app as app_dev

# install
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

# run composer dump-autoload --optimize
RUN composer dump-autoload --optimize

# Xdebug has different modes / functionalities. We can default to 'off' and set to 'debug'
# when we run docker compose up if we need it
# ENV XDEBUG_MODE=off

# Copy xdebug config file into container
COPY ./php/conf.d/xdebug.ini $PHP_INI_DIR/conf.d/

# Install xdebug
RUN set -eux; \
	install-php-extensions xdebug