<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 14.08.16
 * Time: 14:51
 */

namespace Ainias\Core\View\Navigation;


use Zend\View\Helper\Navigation;

class FoundationNavigation extends Navigation
{
    public function getPluginManager()
    {
        if (null === $this->plugins) {
            $this->setPluginManager(new FoundationPluginManager($this->getServiceLocator()));
        }
        return $this->plugins;
    }
}