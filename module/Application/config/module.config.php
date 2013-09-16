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
        'course' => 1.3
    )
);