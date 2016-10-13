<?php

namespace Ainias\Core;

use Ainias\Core\Connections\MyConnection;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return array(
    'doctrine' => array(
        'connection' => array(
            'default' => array(
                'wrapperClass' => MyConnection::class,
                'params' => [],
            )
        ),
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
