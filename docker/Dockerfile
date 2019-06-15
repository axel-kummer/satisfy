FROM php:7.2-apache

ARG GIT_BRANCH=master

RUN apt-get update -q \
    && apt-get install -qy git cron supervisor wget zip unzip\
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

ADD conf/supervisor/* /etc/supervisor/conf.d/
ADD conf/cron.conf /etc/cron.d/satisfy
ADD conf/apache.conf /etc/apache2/sites-available/000-default.conf
ADD conf/php.ini /usr/local/etc/php/conf.d/php.ini
RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir="/usr/bin/" --filename="composer"

RUN composer global require hirak/prestissimo


RUN cd /var/www/ \
    && git clone -b ${GIT_BRANCH} https://github.com/axel-kummer/satisfy.git \
    && cd satisfy \
    && composer install --prefer-dist --no-ansi --no-interaction


RUN cd /var/www/satisfy \
    && rm -rf .git/ .gitignore .php_cs.dist .phpstorm.meta.php .travis.yml docker/ docker-compose.yml ide-twig.json \
    phpstan.neon.dist phpunit.xml.dist tests LICENSE README.md

RUN mkdir /var/www/.ssh \
    && chown -R www-data:www-data /var/www \
    && echo "StrictHostKeyChecking no\n" >> /var/www/.ssh/config \
    && chmod +x /var/www/satisfy/entrypoint.sh

ARG APP_PATH=/var/www/satisfy

WORKDIR /var/www/satisfy

EXPOSE 80

ENTRYPOINT ["/var/www/satisfy/entrypoint.sh"]

CMD ["supervisord", "-n"]
