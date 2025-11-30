# Беремо офіційний образ PHP 8.2-FPM
FROM php:8.2-fpm

# Встановлюємо розширення для роботи з MySQL
RUN docker-php-ext-install pdo_mysql

# === НОВІ РЯДКИ: Встановлюємо Git та Composer ===

# 1. Оновлюємо список пакетів та встановлюємо git, curl та unzip
# (Вони потрібні для Composer, щоб завантажувати пакети)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip 

# 2. Встановлюємо Composer глобально (щоб команда 'composer' була доступна)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

