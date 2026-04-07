FROM php:8.4-fpm

WORKDIR /var/www/html

# Install system dependencies and extension build dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Install basic PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application code
COPY . .

# Install dependencies (with error handling)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || (echo "Composer install failed, retrying..." && composer install --no-interaction --prefer-dist --no-dev)

# Expose port
EXPOSE 9150

# Start command
CMD ["sh", "-lc", "cp .env.docker .env 2>/dev/null || true && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9150"]
