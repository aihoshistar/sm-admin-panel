# ==============================================================================
# SM Admin Panel - Laravel Production Dockerfile
# ==============================================================================
# Multi-stage build for optimized production image
# ==============================================================================

# Stage 1: Composer dependencies
FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Stage 2: Node.js build
FROM node:22-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
# 수정됨: --only=production 제거 및 npm install 사용 (Alpine 호환 네이티브 바인딩 설치를 위해)
RUN npm install
COPY . .
RUN npm run build

# Stage 3: Production image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apk del .build-deps

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . .

# 복사된 로컬 캐시 파일들을 삭제
RUN rm -f /var/www/html/bootstrap/cache/*.php

# Copy Composer dependencies from builder
COPY --from=composer --chown=www-data:www-data /app/vendor ./vendor

# Copy Node.js built assets from builder
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# 수정됨: Stage 3에서 composer 명령어를 사용하기 위해 Stage 1에서 composer 바이너리 복사
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Generate optimized autoloader
RUN composer dump-autoload --optimize --classmap-authoritative

# Set permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Copy configuration files
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# supervisor 로그 폴더 생성
RUN mkdir -p /var/log/supervisor

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD php artisan inspire || exit 1

# Expose port
EXPOSE 80

# Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
