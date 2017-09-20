#!/bin/bash

export SYMFONY_ENV=prod

if [ ! -f "composer.phar" ]; then
    # get latest composer.phar
    curl -s http://getcomposer.org/installer | php
fi

php composer.phar install --no-dev --optimize-autoloader
php composer.phar dump-autoload --optimize --no-dev --classmap-authoritative
php app/console assets:install --env=prod
php app/console cache:clear --env=prod --no-debug --no-warmup