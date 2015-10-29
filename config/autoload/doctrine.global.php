<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
//                        'port' => '3306',
                    'dbname' => 'kicker_league',
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
