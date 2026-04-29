FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2dismod mpm_prefork || true

RUN rm -f /etc/apache2/mods-enabled/mpm_*.load || true
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf || true

RUN a2enmod mpm_prefork

RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]