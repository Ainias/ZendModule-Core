<?php

namespace Ainias\Core;

use Ainias\Core\Controller\ServiceActionController;
use Ainias\Core\Factory\Controller\ServiceActionControllerFactory;

return array(
    'controllers' => [
        'factories' => [
            ServiceActionController::class => ServiceActionControllerFactory::class,
        ],
    ],
);