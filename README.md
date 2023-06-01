Steps
    1- composer install;cp .env.example .env;php artisan key:generate;php artisan cache:clear;php artisan view:clear;bash ./vendor/bin/sail up -d;timeout 5;
    2- php artisan migrate;
    3- php artisan passport:install; php artisan db:seed;
    
