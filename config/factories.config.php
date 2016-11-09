<?php
namespace Ainias\Core;

use Ainias\Core\Factory\MailFactory;
use Ainias\Core\Model\SmtpMail;
use Zend\Navigation\Service\NavigationAbstractServiceFactory;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            NavigationAbstractServiceFactory::class,
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'mail' => SmtpMail::class,
        ),
        'factories' => array(
            'navigation' => \Zend\Navigation\Service\DefaultNavigationFactory::class,
            SmtpMail::class => MailFactory::class
        ),
    ),
);