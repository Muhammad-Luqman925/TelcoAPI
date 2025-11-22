# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install library yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo_mysql

# Aktifkan mod_rewrite untuk Apache (Wajib buat Laravel)
RUN a2enmod rewrite

# Set folder kerja
WORKDIR /var/www/html

# Copy file composer dan install dependensi
COPY composer.lock composer.json /var/www/html/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Copy sisa source code
COPY . /var/www/html

# Ubah permission folder storage (biar bisa upload/log)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ubah document root Apache ke folder public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# Buka port 80
EXPOSE 80