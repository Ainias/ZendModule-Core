<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ainias\Core;

use Ainias\Core\Connections\MyConnection;
use Ainias\Core\Model\Doctrine\DatabaseListener;
use Doctrine\ORM\EntityManager;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $db = "silas";
        $useStrict = false;
        $serviceLocator = $e->getApplication()->getServiceManager();
        $config = $serviceLocator->get('config');
        if (is_array($config) && isset($config["dbDefault"])) {
            $dbConfig = $config["dbDefault"];
            MyConnection::setDefaults($dbConfig);
            $db = $dbConfig["dbname"];
            $useStrict = (isset($dbConfig["useStrict"]))?$dbConfig["useStrict"]:false;
        }

        if ($e->getRequest() instanceof Request)
        {
            $this->bootstrapSession($e);
        }

        /** @var EntityManager $doctrineEntityManager */
        $doctrineEntityManager = $serviceLocator->get("doctrine.entitymanager.orm_default");
        $doctrineEventManager = $doctrineEntityManager->getEventManager();
        $doctrineEventManager->addEventSubscriber(new DatabaseListener($db, $useStrict));
    }

    public function getConfig()
    {
        $config = array();
        foreach (glob(__DIR__ . '/../config/*.config.php') as $filename) {
            $config = array_merge_recursive($config, include($filename));
        }
        return $config;
    }

    public function bootstrapSession(MvcEvent $e)
    {
        $session = $e->getApplication()->getServiceManager()->get(SessionManager::class);
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request = $serviceManager->get('Request');

            $session->regenerateId(true);
            $container->init = 1;
            $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');

            $config = $serviceManager->get('Config');
            if (!isset($config['session'])) {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();

                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }

                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                SessionManager::class => function ($container) {
                    $config = $container->get('config');
                    if (!isset($config['session'])) {
                        $sessionManager = new SessionManager();
                        Container::setDefaultManager($sessionManager);
                        return $sessionManager;
                    }

                    $session = $config['session'];

                    $sessionConfig = null;
                    if (isset($session['config'])) {
                        $class = isset($session['config']['class'])
                            ? $session['config']['class']
                            : SessionConfig::class;

                        $options = isset($session['config']['options'])
                            ? $session['config']['options']
                            : [];

                        $sessionConfig = new $class();
                        $sessionConfig->setOptions($options);
                    }

                    $sessionStorage = null;
                    if (isset($session['storage'])) {
                        $class = $session['storage'];
                        $sessionStorage = new $class();
                    }

                    $sessionSaveHandler = null;
                    if (isset($session['save_handler'])) {
                        // class should be fetched from service manager
                        // since it will require constructor arguments
                        $sessionSaveHandler = $container->get($session['save_handler']);
                    }

                    $sessionManager = new SessionManager(
                        $sessionConfig,
                        $sessionStorage,
                        $sessionSaveHandler
                    );

                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
            ],
        ];
    }
}
