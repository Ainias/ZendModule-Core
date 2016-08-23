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

            'doctrine.entitymanager.' . __NAMESPACE__ => new \DoctrineORMModule\Service\EntityManagerFactory(__NAMESPACE__),
            'doctrine.connection.' . __NAMESPACE__ => new \DoctrineORMModule\Service\DBALConnectionFactory(__NAMESPACE__),
            'doctrine.configuration.' . __NAMESPACE__ => new \DoctrineORMModule\Service\ConfigurationFactory(__NAMESPACE__),
            SmtpMail::class => MailFactory::class
        ),
    ),
);