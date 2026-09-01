FROM php:8.2-fpm

# Install php extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install required PHP extensions for Laravel (including gd, pdo_mysql, and zip)
RUN install-php-extensions bcmath ctype curl dom fileinfo filter gd hash intl mbstring openssl pcre pdo pdo_mysql session tokenizer xml zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application code into container
COPY . /var/www

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for storage and bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Start HTTP server on 0.0.0.0:$PORT
CMD ["sh", "-c", "php artisan storage:link || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
