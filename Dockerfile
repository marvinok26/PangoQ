# Multi-stage build for PangoQ Laravel Application

# Stage 1: Frontend Builder
FROM node:18-alpine AS frontend-builder

WORKDIR /app

# Copy package files and install ALL dependencies (including dev dependencies for build)
COPY package*.json ./
RUN npm ci

# Copy source files and build
COPY . .
RUN npm run build

# Stage 2: Composer Builder
FROM composer:2.7 AS composer-builder

WORKDIR /app

# Copy all application files first
COPY . .

# Ensure AdminHelper.php exists before composer operations
RUN mkdir -p app/Helpers && \
    if [ ! -f app/Helpers/AdminHelper.php ]; then \
        echo "<?php" > app/Helpers/AdminHelper.php && \
        echo "" >> app/Helpers/AdminHelper.php && \
        echo "/**" >> app/Helpers/AdminHelper.php && \
        echo " * Admin Helper Functions" >> app/Helpers/AdminHelper.php && \
        echo " * This file contains helper functions for admin operations" >> app/Helpers/AdminHelper.php && \
        echo " */" >> app/Helpers/AdminHelper.php && \
        echo "" >> app/Helpers/AdminHelper.php && \
        echo "if (!function_exists('admin_helper_example')) {" >> app/Helpers/AdminHelper.php && \
        echo "    function admin_helper_example() {" >> app/Helpers/AdminHelper.php && \
        echo "        return 'Admin helper loaded successfully';" >> app/Helpers/AdminHelper.php && \
        echo "    }" >> app/Helpers/AdminHelper.php && \
        echo "}" >> app/Helpers/AdminHelper.php; \
    fi

# Verify the file exists
RUN ls -la app/Helpers/AdminHelper.php

# Install composer dependencies without scripts first
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader \
    --prefer-dist

# Generate optimized autoload files
RUN composer dump-autoload --optimize --classmap-authoritative

# Stage 3: Final Runtime Image
FROM php:8.2-cli-alpine

# Set metadata
LABEL maintainer="mesandyelaine@gmail.com"
LABEL description="PangoQ Laravel Trip Planning Application"
LABEL version="1.0.0"

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    zip \
    unzip \
    git \
    mysql-client \
    bash \
    netcat-openbsd \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pcntl

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Set working directory
WORKDIR /var/www/html

# Create non-root user for security
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Copy application files from composer stage
COPY --from=composer-builder --chown=www:www /app .

# Copy built frontend assets from frontend stage
COPY --from=frontend-builder --chown=www:www /app/public/build ./public/build

# Copy environment and startup files
COPY --chown=www:www .env.docker .env
COPY --chown=www:www startup.sh /usr/local/bin/startup.sh

# Create required directories and set permissions
RUN mkdir -p \
    storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache \
    && chown -R www:www \
    storage \
    bootstrap/cache \
    && chmod -R 775 \
    storage \
    bootstrap/cache \
    && chmod +x /usr/local/bin/startup.sh

# Copy PHP configuration
COPY docker/php.ini /usr/local/etc/php/php.ini

# Switch to non-root user
USER www

# Run Laravel setup commands
RUN php artisan key:generate --ansi && \
    php artisan storage:link && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expose port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s \
    --timeout=10s \
    --start-period=60s \
    --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

# Start the application
CMD ["/usr/local/bin/startup.sh"]