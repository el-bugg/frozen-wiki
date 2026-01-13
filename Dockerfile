# 1. Gunakan PHP 8.4
FROM php:8.4-apache

# 2. Instal dependensi sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev

# 3. Instal ekstensi PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# 4. Konfigurasi Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Instal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copy File Proyek
WORKDIR /var/www/html
COPY . .

# 7. Jalankan Instalasi dengan Paksa (Mengabaikan cek versi PHP yang ketat)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 8. Set Izin Folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Menambahkan perintah pembuatan database otomatis saat container start
RUN touch database/database.sqlite && chmod -R 777 database

EXPOSE 80

CMD ["apache2-foreground"]