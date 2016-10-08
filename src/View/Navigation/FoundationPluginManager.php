<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 14.08.16
 * Time: 14:47
 */

namespace Ainias\Core\View\Navigation;


use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\View\Helper\Navigation\Breadcrumbs;
use Zend\View\Helper\Navigation\Links;
use Zend\View\Helper\Navigation\PluginManager;
use Zend\View\Helper\Navigation\Sitemap;

class FoundationPluginManager extends PluginManager
{
    protected $aliases = [
        'breadcrumbs' => Breadcrumbs::class,
        'links' => Links::class,
        'menu' => FoundationMenuHelper::class,
        'sitemap' => Sitemap::class,
    ];

    protected $factories = [
        Breadcrumbs::class => InvokableFactory::class,
        Links::class => InvokableFactory::class,
        FoundationMenuHelper::class => InvokableFactory::class,
        Sitemap::class => InvokableFactory::class,

        // v2 canonical FQCNs

        'zendviewhelpernavigationbreadcrumbs' => InvokableFactory::class,
        'zendviewhelpernavigationlinks' => InvokableFactory::class,
        'zendviewhelpernavigationmenu' => InvokableFactory::class,
        'zendviewhelpernavigationsitemap' => InvokableFactory::class,
    ];
}