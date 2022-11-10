#!/usr/bin/env bash
#@see https://laravel-news.com/laravel-scheduler-queue-docker
set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$role" = "app" ]; then
    echo "Caching configuration..."
    ( cd /var/www/html && \
    php artisan config:clear \
      && php artisan clear-compiled \
      && php artisan route:clear \
      && php artisan view:clear \
      && php artisan view:cache \
      && rm -rf public/storage \
      && php artisan storage:link \
      && chown www-data:www-data storage/app/ -R \
      && php artisan cache:clear \
      && php artisan config:cache )
fi

if [ "$role" = "app" ]; then
    # exec php-fpm
    exec apache2-foreground

elif [ "$role" = "queue" ]; then

    echo "Running the queue..."
    php /var/www/html/artisan horizon
    # php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
      php /var/www/html/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
