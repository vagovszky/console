Console Chance import tool
===========================

Import courses from Chance

installation
------------
- change dir to project root
- php composer.phar install
- setup doctrine - cp config/autoload/doctrine.local.php.dist config/autoload/doctrine.local.php & edit
- create database - ./vendor/bin/doctrine-module orm:schema-tool:create

usage:
    php index.php import

phar creation:
    ./bin/create_phar