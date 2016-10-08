<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 14.08.16
 * Time: 13:58
 */

namespace Ainias\Core\Factory\View;

use Ainias\Core\View\Navigation\FoundationNavigation;
use Interop\Container\ContainerInterface;
use ReflectionProperty;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Navigation as NavigationHelper;

class FoundationMenuHelperFactory implements FactoryInterface
{
    /**
     * Create and return a navigation helper instance. (v3)
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return FoundationNavigation
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new FoundationNavigation();
        $helper->setServiceLocator($this->getApplicationServicesFromContainer($container));
        return $helper;
    }

    /**
     * Create and return a navigation helper instance. (v2)
     *
     * @param ServiceLocatorInterface $container
     * @param null|string $name
     * @param string $requestedName
     * @return NavigationHelper
     */
    public function createService(
        ServiceLocatorInterface $container,
        $name = null,
        $requestedName = NavigationHelper::class
    ) {
        return $this($container, $requestedName);
    }

    /**
     * Retrieve the application (parent) services from the container, if possible.
     *
     * @param ContainerInterface $container
     * @return ContainerInterface
     */
    private function getApplicationServicesFromContainer(ContainerInterface $container)
    {
        // v3
        if (method_exists($container, 'configure')) {
            $r = new ReflectionProperty($container, 'creationContext');
            $r->setAccessible(true);
            return $r->getValue($container) ?: $container;
        }

        // v2
        return $container->getServiceLocator() ?: $container;
    }
}