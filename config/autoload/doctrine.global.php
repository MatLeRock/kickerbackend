<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host' => 'ec2-54-225-199-108.compute-1.amazonaws.com',
                    'port' => '5432',
                    'dbname' => 'd9quivfc4499v1',
                    'user' => filter_input(INPUT_ENV, "KICKER_USER"),
                    'password' => filter_input(INPUT_ENV, "KICKER_PASSWORD"),
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
