FROM php:7.4.33-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    zip \
    gd \
    pdo_mysql \
    opcache

# Install Composer 1.9.1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.9.1

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache VirtualHost
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Increase PHP memory limit
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Copy composer files for caching
COPY composer.json composer.lock ./

# Install dependencies (without scripts to avoid issues with missing files if any)
RUN php -d memory_limit=-1 /usr/local/bin/composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Create var directory and set permissions
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var public && \
    chmod -R 775 var public

# Finish composer installation with autoloader and scripts
RUN php -d memory_limit=-1 /usr/local/bin/composer dump-autoload --optimize && \
    php -d memory_limit=-1 /usr/local/bin/composer run-script post-install-cmd && \
    php bin/console assets:install public

# Expose port
EXPOSE 80
