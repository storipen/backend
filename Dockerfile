# FROM php:7.4-fpm-alpine

# RUN apk add --no-cache nginx wget

# RUN mkdir -p /run/nginx

# COPY docker/nginx.conf /etc/nginx/nginx.conf

# RUN mkdir -p /app
# COPY . /app

# RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
# RUN cd /app && \
#     /usr/local/bin/composer install --no-dev
# RUN docker-php-ext-install pdo pdo_mysql mbstring

# RUN chown -R www-data: /app

# CMD sh /app/docker/startup.sh

FROM php:7

ENV PORT=8080
ENV HOST=0.0.0.0

RUN apt-get update -y \
  && apt-get install --no-install-recommends -y openssl zip unzip git libonig-dev \
  && apt-get clean \
  && zlib1g-dev  \
  && rm -rf /var/lib/apt/lists/*

RUN ["/bin/bash", "-c", "set -o pipefail && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"]
RUN docker-php-ext-install pdo pdo_mysql mbstring
RUN docker-php-ext-install gd
WORKDIR /app
COPY . /app
RUN composer validate && composer install


EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]