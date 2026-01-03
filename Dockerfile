# Stage 1: PHP Dependencies
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# Stage 2: Frontend Assets
FROM node:20-alpine AS frontend
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

# Enable Apache modules for performance
RUN a2enmod rewrite expires headers deflate

# Update Apache configuration to point to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Increase PHP Upload Limits and Tune OPcache for High Performance (16GB RAM VPS)
RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=1024M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.memory_consumption=512" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.interned_strings_buffer=64" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.max_accelerated_files=65536" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache-optimized.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache-optimized.ini

# Add Browser Caching and Gzip Compression Config
RUN echo '<IfModule mod_expires.c>\n\
    ExpiresActive On\n\
    ExpiresDefault "access plus 1 month"\n\
    ExpiresByType image/x-icon "access plus 1 year"\n\
    ExpiresByType image/jpeg "access plus 1 year"\n\
    ExpiresByType image/png "access plus 1 year"\n\
    ExpiresByType image/gif "access plus 1 year"\n\
    ExpiresByType image/webp "access plus 1 year"\n\
    ExpiresByType text/css "access plus 1 month"\n\
    ExpiresByType text/javascript "access plus 1 month"\n\
    ExpiresByType application/javascript "access plus 1 month"\n\
    ExpiresByType application/x-javascript "access plus 1 month"\n\
    ExpiresByType application/pdf "access plus 1 month"\n\
    ExpiresByType application/x-shockwave-flash "access plus 1 month"\n\
    ExpiresByType font/truetype "access plus 1 year"\n\
    ExpiresByType font/opentype "access plus 1 year"\n\
    ExpiresByType application/x-font-woff "access plus 1 year"\n\
    ExpiresByType image/svg+xml "access plus 1 year"\n\
    </IfModule>\n\
    <IfModule mod_deflate.c>\n\
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/x-javascript application/json\n\
    </IfModule>' > /etc/apache2/conf-available/performance.conf \
    && a2enconf performance

# Copy application files
COPY . .

# Copy vendor from Stage 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy build files from Stage 2
COPY --from=frontend /app/public/build /var/www/html/public/build

# Setup Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions for storage, cache and uploads
RUN mkdir -p /var/www/html/public/photos /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/photos /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/photos /var/www/html/public/uploads

# Environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose port 80
EXPOSE 80

# Use our entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
