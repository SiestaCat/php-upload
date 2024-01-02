FROM serversideup/php:8.2-fpm-nginx

ENV APP_PATH="/var/www/html"
ENV APP_ENV="prod"

RUN apt-get update -y

RUN apt-get install -y --no-install-recommends git php8.2-intl php8.2-xml php8.2-zip php8.2-curl php8.2-mbstring php8.2-mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR ${APP_PATH}

RUN mkdir -p ${APP_PATH}/flysystem/storage/default

COPY . ${APP_PATH}
RUN chown -R webuser:webgroup ${APP_PATH}

USER webuser

RUN composer install --no-scripts --no-dev

USER root