# Stage 1: PHP Dependencies
FROM composer:2.7 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# Stage 2: Frontend Assets
FROM node:20-alpine as frontend
WORKDIR /app
COPY package.json ./
# Handle missing package-lock.json or yarn.lock
COPY package*.json ./ 
RUN npm install
COPY . .
RUN npm run build

# Stage 3: Final Production Image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache configuration to point to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application files
COPY . .

# Copy vendor from Stage 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy build files from Stage 2
COPY --from=frontend /app/public/build /var/www/html/public/build

# Setup Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions for storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose port 80
EXPOSE 80

# Use our entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
