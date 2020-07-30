FROM php:7.4-cli

MAINTAINER Tony Vlček <vlcek.a@email.cz>

# Install additional extensions
# Run docker run [package] php -m to see a list of enabled extensions
RUN docker-php-ext-install pcntl

# Install XDEBUG
RUN pecl install xdebug
COPY ./.docker/xdebug/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Install composer + prestissimo
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /banot-api
WORKDIR /banot-api

# Install dependencies
RUN composer install
