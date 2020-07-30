FROM webdevops/php-nginx-dev:7.4-alpine

COPY ./.docker/nginx/vhost.conf /opt/docker/etc/nginx/vhost.conf
COPY ./.docker/php/php.ini /opt/docker/etc/php

COPY . ./app

RUN chmod -R 777 /app/temp/ /app/log/
