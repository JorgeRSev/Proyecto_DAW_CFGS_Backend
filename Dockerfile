FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN rm -f /etc/apache2/mods-enabled/mpm_*.load
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf

RUN a2enmod mpm_prefork

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

RUN echo "ESTOY USANDO DOCKERFILE" && sleep 5


EXPOSE 80