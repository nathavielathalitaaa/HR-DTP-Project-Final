FROM php:8.2-fpm

COPY --from=mlocati/php-extension-installer /usr/local/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions ctype curl dom fileinfo filter gd hash mbstring openssl pcre pdo session tokenizer xml

WORKDIR /var/www

COPY . /var/www

CMD ["php-fpm"]
