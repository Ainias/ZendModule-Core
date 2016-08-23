<?php

namespace Ainias\Core;

use Ainias\Core\Controller\ServiceActionController;
use Ainias\Core\Factory\Controller\ServiceActionControllerFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'controllers' => [
        'factories' => [
            ServiceActionController::class => ServiceActionControllerFactory::class,
        ],
    ],
);