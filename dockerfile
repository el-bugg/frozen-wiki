# Menggunakan image PHP resmi dengan Apache
FROM php:8.2-apache

# Instal dependensi sistem yang diperlukan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev

# Instal ekstensi PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Aktifkan Apache Rewrite Module (penting untuk routing Laravel)
RUN a2enmod rewrite

# Set Document Root Apache ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua kode aplikasi ke dalam container
WORKDIR /var/www/html
COPY . .

# Jalankan instalasi composer
RUN composer install --no-dev --optimize-autoloader

# Set izin folder (penting agar Laravel bisa menulis log/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port yang digunakan oleh Render
EXPOSE 80

# Jalankan perintah start
CMD ["apache2-foreground"]