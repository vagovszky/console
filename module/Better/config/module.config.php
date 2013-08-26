<?php
return array(
    'better' => array(
        'wd_host' => 'http://localhost:4444/wd/hub',
        'browser' => 'firefox'
    ),
    'controllers' => array(
        'invokables' => array(
            'Better\Controller\Bet' => 'Better\Controller\BetController'
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'bet' => array(
                    'options' => array(
                        'route' => 'bet <odd_id> <money>',
                        'defaults' => array(
                            'controller' => 'Better\Controller\Bet',
                            'action' => 'bet'
                        )
                    )
                )
            )
        )
    ),
);