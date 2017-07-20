Obon
==========

### Requirements
    -   PHP 7
    -   ffmpeg

### Installation
    php composer.phar install
    php app/console assets:install
    php app/console doctrine:fixtures:load
    php app/console server:run

### Useful links
1. [Material Design Principles](https://material.google.com/)

2. [Material Design](https://getmdl.io/)

3. [Home Page Template](https://getmdl.io/templates/android-dot-com/index.html)

4. [Sonata Admin](https://sonata-project.org/bundles/)



### Installation

    php composer.phar install
    php bin/console cache:clear --env=prod
    php bin/console assets:install

#### Useful commands

    php composer.phar show --platform #show loaded extensions

### Docker Commands

Login to container

    sudo docker exec -i -t trhqistraining_hqri /bin/bash

Remove Everything docker has

    docker kill $(docker ps -q); docker rm $(docker ps -a -q); docker rm $(docker ps -a -q); docker rmi $(docker images -q -f dangling=true); docker rmi $(docker images -q)

### Create admin user
    php bin/console fos:user:create admin nursultan.turdaliev@triprebel.com password
    php bin/console fos:user:promote admin ROLE_ADMIN

### Running Tests

    docker-compose -f docker-compose.test.yml up