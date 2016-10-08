<?php

namespace Ainias\Core\Factory;

use Ainias\Core\Model\SmtpMail;
use Interop\Container\ContainerInterface;
use Zend\Http\Request;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Helper\BasePath;

class MailFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createService($container);
    }
    public function createService(ContainerInterface $sm)
    {
        $request = $sm->get("Request");
        /** @var BasePath $basePath */
        $basePath = $sm->get("ViewHelperManager")->get("basePath");
        if ($request instanceof Request) {
            $uri = $request->getUri();
            $url = $uri->getScheme() . "://" . $uri->getHost() . $basePath->__invoke();
        }
        else {
            $url = "";
        }
        $config = $sm->get('config');
        /** @var BasePath $basePath */
        $mail = new SmtpMail($config['mailEinstellungen']['options'], $config['mailEinstellungen']['sender'], $config["systemVariables"], $url);
        return $mail;
    }
}