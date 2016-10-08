<?php

namespace Ainias\Core;

use Ainias\Core\Factory\View\FoundationMenuHelperFactory;
use Ainias\Core\View\DatatablesHelper;
use Ainias\Core\View\Form\FormElementErrors;
use Ainias\Core\View\Form\FoundationFormHelper;
use Ainias\Core\View\FormatDatetimeGerman;
use Ainias\Core\View\NoJs;
use Ainias\Core\View\NoTag;
use Ainias\Core\View\PriceFormatter;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'view_helpers' => array(
        'factories' => array(
            NoJs::class => InvokableFactory::class,
            NoTag::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
            FoundationFormHelper::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
            DatatablesHelper::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
            'navigation' => FoundationMenuHelperFactory::class,
            FormatDatetimeGerman::class => InvokableFactory::class,
            PriceFormatter::class => InvokableFactory::class,

        ),
        'aliases' => [
            'noJs' => NoJs::class,
            'noTag' => NoTag::class,
            'datatable' => DatatablesHelper::class,
            'foundationForm' => FoundationFormHelper::class,
            'formatGerman' => FormatDatetimeGerman::class,
            'formatPrice' => PriceFormatter::class,
        ],
        'invokables' => [
            'form_element_errors' => FormElementErrors::class,
        ]
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
//            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
//            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
////            'layout/ajax' => __DIR__ . '/../view/layout/ajax.phtml',
//            'error/404' => __DIR__ . '/../view/error/404.phtml',
////            'error/403' => __DIR__ . '/../view/error/403.phtml',
//            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
);