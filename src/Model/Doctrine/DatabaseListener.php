<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 20.07.16
 * Time: 17:38
 */

namespace Ainias\Core\Model\Doctrine;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Exception;
use ReflectionClass;

class DatabaseListener implements EventSubscriber
{
    /** @var null|AnnotationReader */
    private static $annotationReader = null;

    private function getAnnotationReader()
    {
        if (self::$annotationReader == null) {
            self::$annotationReader = new AnnotationReader();
        }
        return self::$annotationReader;
    }

    public function getSubscribedEvents()
    {
        return Array(Events::loadClassMetadata);
    }

    public function __construct(EntityManager $db)
    {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $schemaName = $event->getClassMetadata()->getSchemaName();
        if ($schemaName == null) {

            $dbName = trim(explode("\\", $event->getClassMetadata()->namespace)[0]);
            if ($dbName == "") {
                $dbName = "silas";
            } else {
                $dbName = "silas_" . $dbName;
            }
            $event->getClassMetadata()->table["schema"] = $dbName;
        }
    }
}