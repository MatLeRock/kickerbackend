Kicker League backend built with Zend Framework 2 for use with KickerClient
=======================

Introduction
------------
This is based on the ZendSkeletonApplication.

Installation
------------

Using composer.phar
----------------------------

    cd [root-folder]
    (php composer.phar self-update)     | The *self-update* directive is to ensure you have an up-to-date `composer.phar`available.)
    php composer.phar install
    (php composer.phar update)

Apache Server Setup for dev
----------------

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName kickerleague_api.local
        DocumentRoot /path/to/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

Add an entry for kickerleague_api.local in your hosts file
127.0.0.1   kickerleague_api.local

Doctrine commands
-------------------
    cd root-folder
    vendor\bin\doctrine-module  orm:validate-schema         | Test sync of Code (entities) and DB-Schema
                                orm:schema-tool:create      | Creates DB-schema

Swagger Docs
----------------
http://www.davenewson.com/tutorials/php/rest-api-documentation-in-zend-framework-2-with-swagger  
https://github.com/outeredge/SwaggerModule  
http://zircote.com/swagger-php

API Security
----------------------------
API is secured with HTTP Digest Authentication.   
Credentials can be added as Environment Variables (e.g. via Apache SetEnv).   
Vars to set: API_AUTH_PASS, API_AUTH_USER, API_AUTH_REALM  
API_AUTH_PASS = md5(API_AUTH_USER:API_AUTH_REALM:API_AUTH_PASS)    

Alternatively is can be set via passwords-File (/files/passwords).  
Syntax: [username]:[realm]:[password]  
