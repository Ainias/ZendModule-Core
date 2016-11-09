<?php

namespace Ainias\Core;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return array(
    'doctrine' => array(
        'driver' => array(
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__.'\Model' => 'entities_default',
                ),
            ),
            'entities_default' => array(
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Model',
                )
            )
        ),
        'entitymanager' => array(
            'orm_default' => [
                'connection' => 'default',
            ],
        ),
    ),
);
