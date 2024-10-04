FROM php:8.1-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Expose port 8000
EXPOSE 8000

COPY . .

# Use PHP's built-in server to serve the application
CMD ["php", "-S", "0.0.0.0:8000"]