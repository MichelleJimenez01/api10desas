# Imagen base con PHP 8.2 y Composer
FROM php:8.2-cli

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Definir carpeta de trabajo
WORKDIR /app

# Copiar todo el proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar cache de configuración
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Exponer puerto (Render usa el 10000 por defecto)
EXPOSE 10000

# Iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
