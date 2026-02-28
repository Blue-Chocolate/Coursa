FROM php:8.2-fpm

# System dependencies + Chromium for Browsershot/PDF generation
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libzip-dev libonig-dev \
    nodejs npm \
    chromium chromium-driver \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath pcntl \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Tell Browsershot to use system Chromium instead of downloading its own
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application source
COPY . .
RUN composer dump-autoload --optimize --no-dev

# Build frontend assets
RUN npm ci && npm run build && rm -rf node_modules

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && mkdir -p storage/app/certificates

EXPOSE 9000
CMD ["php-fpm"]