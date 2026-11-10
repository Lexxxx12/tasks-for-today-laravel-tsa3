FROM php:8.4-apache
RUN apt-get update && apt-get install -y --no-install-recommends libsqlite3-dev libonig-dev unzip git && docker-php-ext-install pdo_sqlite mbstring && a2enmod rewrite && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader && chown -R www-data:www-data storage bootstrap/cache database
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf && printf '<Directory /var/www/html/public>\nAllowOverride All\nRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf && a2enconf laravel
COPY docker/start.sh /usr/local/bin/start-app
RUN chmod +x /usr/local/bin/start-app
EXPOSE 80
CMD ["start-app"]
