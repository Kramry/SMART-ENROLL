ARG PHP_VERSION=8.5
FROM php:${PHP_VERSION}-apache

ENV PORT=10000
ENV APACHE_DOCUMENT_ROOT=/var/www/html/LavaLust-dev-v4-SmartEnroll/LavaLust-dev-v4/public

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf \
    && sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf \
    && sed -ri "s/<VirtualHost \\*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

COPY --chown=www-data:www-data LavaLust-dev-v4-SmartEnroll/LavaLust-dev-v4/ /var/www/html/LavaLust-dev-v4-SmartEnroll/LavaLust-dev-v4/

RUN chmod -R 775 /var/www/html/LavaLust-dev-v4-SmartEnroll/LavaLust-dev-v4/runtime

EXPOSE 10000

CMD ["apache2-foreground"]