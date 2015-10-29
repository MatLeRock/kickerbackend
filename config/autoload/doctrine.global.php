<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host' => 'localhost',
//                        'port' => '3306',
                    'dbname' => 'd9quivfc4499v1',
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies' => false
            )
        )
    )
);
