<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'database_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Database/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Database\Entity' => 'database_entities'
                )
            )))
);