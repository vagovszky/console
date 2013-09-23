<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'default' => array(
                    'options' => array(
                        'route' => 'default',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'default'
                        )
                    )
                ),
                'truncate' => array(
                    'options' => array(
                        'route' => 'truncate',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'truncate'
                        )
                    )
                ),
                'info' => array(
                    'options' => array(
                        'route' => 'info',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'info'
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'simpleTip' => 'Application\Service\SimpleTipFactory',
        )
    ),
    'simple_tip' => array(
        'profit' => 5,
        'limit' => 400,
        'course' => 1.3,
        'strategyMap' => array(
            1 => array(0, 3),
            2 => array(0.01, 3),
            3 => array(0.02, 3),
            4 => array(0, 6),
            5 => array(0.02, 6),
            6 => array(0.03, 6),
            7 => array(0, 8),
            8 => array(0.02, 8),
            9 => array(0.03, 8),
            10 => array(0,10),
            11 => array(0.2, 12)
        )
    )
);