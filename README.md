# Community Waste Collection API

## Teknologi dan Modul
- Framework: Laravel
- Database: MongoDB
- API Tools: Postman

# Finding mongo DB driver file
https://github.com/mongodb/mongo-php-driver/releases/
tambahkan dll ke


# Put mongodb.dll driver in php and enable
1. Put in php/ext/ folder
2. Enable or add the extension in php.ini [extension=php_mongodb.dll]

# Check if mongo is installed
php -m | findstr mongo

# Library for Laravel mongodb
composer require mongodb/laravel-mongodb

# Clear cache
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear

# php artisan make example
- php artisan make
-> This shows all the possible syntax and file creation in php

- php artisan make:controller HouseholdController
- php artisan make:request HouseholdRequest
- php artisan make:resource HouseholdResource
- php artisan make:class HouseholdService
- php artisan make:class HouseholdRepository
- composer require crestapps/laravel-code-generator

# Postman documentation (Requires sign in)
- https://documenter.getpostman.com/view/952664/2sB3dQwVwn
- Use same API as the client needed (or test / questions)
- Example : GET api/households
- Example : POST api/payments
