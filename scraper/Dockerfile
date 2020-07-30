FROM php:7.4-cli

MAINTAINER Tony Vlček <vlcek.a@email.cz>

# Install additional extensions
# Run docker run [package] php -m to see a list of enabled extensions
RUN docker-php-ext-install pcntl

RUN pecl install xdebug
COPY ./.docker/xdebug/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY . /usr/src/scraper
WORKDIR /usr/src/scraper
#CMD [ "./bin/consumer", "run", "-vv"]
