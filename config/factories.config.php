<?php
namespace Ainias\Core;

use Ainias\Core\Factory\MailFactory;
use Ainias\Core\Model\SmtpMail;
use Zend\Navigation\Service\NavigationAbstractServiceFactory;

$lastNamespacePart = explode("\\", __NAMESPACE__)[1];
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

//            'doctrine.entitymanager.orm_default' => new \DoctrineORMModule\Service\EntityManagerFactory($lastNamespacePart),
//            'doctrine.connection.' . $lastNamespacePart => new \DoctrineORMModule\Service\DBALConnectionFactory($lastNamespacePart),
//            'doctrine.configuration.' . $lastNamespacePart => new \DoctrineORMModule\Service\ConfigurationFactory($lastNamespacePart),
            SmtpMail::class => MailFactory::class
        ),
    ),
);