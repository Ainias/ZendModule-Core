<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 13.07.16
 * Time: 18:09
 */

namespace Ainias\Core\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceActionControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if ($container instanceof ServiceLocatorInterface)
        {
            return new $requestedName($container);
        }
        return null;
    }
}