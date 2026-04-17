FROM php:8.5-fpm

# Использование российских зеркал для более стабильного соединения
RUN sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources 2>/dev/null || \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем composer файлы
COPY app/composer.json app/composer.lock* ./

# Устанавливаем зависимости
RUN if [ -f composer.json ]; then \
        composer install --no-interaction --optimize-autoloader --no-dev; \
    fi

# Копируем остальной код
COPY app/ .

# Устанавливаем права доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/runtime \
    && chmod -R 777 /var/www/html/web/assets

# Команда запуска
CMD ["php-fpm"]