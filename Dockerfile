FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2dismod mpm_prefork || true


RUN a2enmod mpm_prefork


RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

RUN apachectl -M

EXPOSE 80

RUN apachectl -M