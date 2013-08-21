Console Chance import tool
===========================

Import courses from Chance

installation
------------

- php composer.phar install
- setup doctrine - cp config/doctrine.local.php.dist config/doctrine.local.php & edit
- create database - ./vendor/bin/doctrine-module orm:schema-tool:create

usage:
    php index.php import
