FROM php:8.2-apache

RUN a2dismod mpm_event || true && a2enmod mpm_prefork
 
RUN docker-php-ext-install pdo pdo_mysql
 
RUN a2enmod rewrite
 
COPY apache.conf /etc/apache2/sites-available/000-default.conf
 
COPY . /var/www/html
 
COPY start.sh /start.sh
RUN chmod +x /start.sh
 
EXPOSE 80
 
CMD ["/start.sh"]
 