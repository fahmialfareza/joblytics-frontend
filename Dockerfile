FROM webdevops/php-nginx:8.0-alpine

LABEL org.opencontainers.image.authors=fahmialfareza@icloud.com
LABEL org.opencontainers.image.title="Joblytics"
LABEL org.opencontainers.image.licenses=MIT
LABEL com.malanghub.nodeversion=$NODE_VERSION

EXPOSE 8000

WORKDIR /app

COPY . .

RUN composer install
RUN composer dump-autoload

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]