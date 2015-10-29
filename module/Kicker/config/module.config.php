<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'Kicker_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Kicker/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                     'Kicker\Entity' =>  'Kicker_driver'
                ),
            ),
        ),
    ),  
    'controllers' => array(
        'invokables' => array(
            'Kicker\Controller\User' => 'Kicker\Controller\UserController',
            'Kicker\Controller\Game' => 'Kicker\Controller\GameController',
            'Kicker\Controller\GamesTable' => 'Kicker\Controller\GamesTableController',
             'Kicker\Controller\UsersTable' => 'Kicker\Controller\UsersTableController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Kicker\Controller\User',
                    ),
                ),
            ),
            'game' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/game[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Kicker\Controller\Game',
                    ),
                ),
            ),
            'games-table' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/games-table[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Kicker\Controller\GamesTable',
                    ),
                ),
            ),
            'users-table' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/users-table[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Kicker\Controller\UsersTable',
                    ),
                ),
            )
        ),
    ),
);
