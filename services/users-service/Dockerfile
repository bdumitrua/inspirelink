FROM php:8.2-fpm

# Extensions instlling
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev openssl zip unzip git supervisor librdkafka-dev
RUN docker-php-ext-install zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install sockets

# Redis 
RUN pecl install redis-6.0.2 && docker-php-ext-enable redis

# Kafka 
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

WORKDIR /app

COPY . .

RUN composer install --no-scripts

COPY ./kafka_consumers.conf /etc/supervisor/conf.d/kafka_consumers.conf
COPY ./start.sh /app/start.sh

EXPOSE 8000
CMD ["/app/start.sh"]


