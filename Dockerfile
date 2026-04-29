FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN sed -i 's/^LoadModule mpm_event_module/#LoadModule mpm_event_module/' /etc/apache2/mods-available/mpm_event.load || true
RUN sed -i 's/^LoadModule mpm_worker_module/#LoadModule mpm_worker_module/' /etc/apache2/mods-available/mpm_worker.load || true

RUN a2enmod mpm_prefork

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

RUN apachectl -M | grep mpm || true
EXPOSE 80