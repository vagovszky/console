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
                /*
                'default' => array(
                    'type' => 'catchall',
                    'options' => array(
                        'route'=> '',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'default',
                        ),
                    ),
                ),
                */
                'default' => array(
                    'options' => array(
                        'route' => 'default',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'default'
                        )
                    )
                )
            )
        )
    )
);