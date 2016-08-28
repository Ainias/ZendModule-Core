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
use Zend\EventManager\Event;
use Zend\Http\Response;
use Zend\Log\Filter\Priority;
use Zend\Log\Logger;
use Zend\Log\Writer\ChromePhp;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;

class Module
{
    const LOG_DIR = "log";
    const EVENT_LOG = "log";

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
            $useStrict = $dbConfig["useStrict"];
        }

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $logPath = realpath(self::LOG_DIR);
        $logger = new Logger();
        $catchAllWriter = new Stream($logPath . "/log.log");
        $chromePhp = new ChromePhp();
        $logger->addWriter($catchAllWriter);
        $errorWriter = new Stream($logPath . "/error.log");
        $errorWriter->addFilter(new Priority(Logger::ERR));
        $logger->addWriter($errorWriter);
        $errorLogger = new Logger();
        $phpErrorWriter = new Stream($logPath . "/php_error.log");
        $errorLogger->addWriter($catchAllWriter);
        $errorLogger->addWriter($phpErrorWriter);
        $errorLogger->addWriter($chromePhp);
        Logger::registerErrorHandler($errorLogger);
        Logger::registerFatalErrorShutdownFunction($errorLogger);

        $exceptionLogger = new Logger();
        $exceptionWriter = new Stream($logPath . "/php_exceptions.log");
        $exceptionLogger->addWriter($catchAllWriter);
        $exceptionLogger->addWriter($exceptionWriter);
        $exceptionLogger->addWriter($chromePhp);
        Logger::registerExceptionHandler($exceptionLogger);

        $eventManager->getSharedManager()->attach('*', self::EVENT_LOG, function (Event $e) use ($logger) {
            $params = $e->getParams();

            if (isset($params["message"])) {
                if (isset($params["level"]) && ($params["level"] == Logger::ALERT || $params["level"] == Logger::CRIT || $params["level"] == Logger::DEBUG || $params["level"] == Logger::EMERG || $params["level"] == Logger::ERR || $params["level"] == Logger::INFO || $params["level"] == Logger::NOTICE)) {
                    $logLevel = $params["level"];
                } else {
                    $logLevel = Logger::INFO;
                }
                $logger->log($logLevel, $params["message"]);
            }
        });

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'dispatchError'), -1000);
        $eventManager->getSharedManager()->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'dispatchError'), -1000);

        $this->bootstrapSession($e);


        /** @var EntityManager $doctrineEntityManager */
        $doctrineEntityManager = $serviceLocator->get("doctrine.entitymanager.orm_default");
        $doctrineEventManager = $doctrineEntityManager->getEventManager();
        $doctrineEventManager->addEventSubscriber(new DatabaseListener($db, $useStrict));
    }

    public function dispatchError(MvcEvent $e)
    {
        $response = $e->getApplication()->getResponse();
        /** @var ViewModel $viewModel */
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();

        $error = $e->getError();
        /** @var \Exception $exception */
        $exception = $e->getParam("exception");
        if ($exception instanceof \Exception || $exception instanceof \Error) {
            $error = $exception->getMessage() . " - ErrorCode: " . $exception->getCode() . " File: " . $exception->getFile() . " Line: " . $exception->getLine();
        }
        $e->getApplication()->getEventManager()->trigger(self::EVENT_LOG, null, array(
            "message" => $error,
            "level" => Logger::ERR
        ));

        if ($response instanceof Response) {
            if ($response->isOk()) {
                $errorTemplate = "error/index";
            } else {
                switch ($response->getStatusCode()) {
                    case 401:
                    case 403: {
                        $errorTemplate = 'error/403';
                        break;
                    }
                    case 404: {
                        $errorTemplate = 'error/404';
                        break;
                    }
                    default: {
                        $errorTemplate = 'error/statusCode';
                        $viewModel->statusCode = $response->getStatusCode();
                        $viewModel->reasonPhrase = $response->getReasonPhrase();

                        break;
                    }
                }
            }
            $child = new ViewModel();
            $child->setTemplate($errorTemplate);
            $viewModel->addChild($child);

            $e->setViewModel($viewModel);
            $e->stopPropagation(true);
        } elseif ($response instanceof \Zend\Console\Response) {
            //TODO error für Console
            echo "ErrorLevel: " . $response->getErrorLevel() . PHP_EOL;
        }
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
