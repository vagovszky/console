<?php
return array(
     'controllers' => array(
        'invokables' => array(
            'Import\Controller\Import' => 'Import\Controller\ImportController'
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'import' => array(
                    'options' => array(
                        'route' => 'import',
                        'defaults' => array(
                            'controller' => 'Import\Controller\Import',
                            'action' => 'import'
                        )
                    )
                )
            )
        )
    ),
    'sources' => array(
        'courses' => 'https://www.chance.cz/cs/chance/vyhledavani/xml?obdobi=2&vypisovat=1&pozadavek=vypis',
        'results' => 'https://www.chance.cz/cs/chance/vyhledavani/xml?datum_od=_DATE_%2000:00&obdobi=5&radit=2,1&vypisovat=2&pozadavek=vypis',
    ),
);