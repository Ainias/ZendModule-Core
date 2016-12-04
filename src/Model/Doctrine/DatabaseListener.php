<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 20.07.16
 * Time: 17:38
 */

namespace Ainias\Core\Model\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

class DatabaseListener implements EventSubscriber
{
    private $useStrict;

    private $prefix;

    public function getSubscribedEvents()
    {
        return Array(Events::loadClassMetadata);
    }

    public function __construct($prefix = 'silas', $useStrict = false)
    {
        $this->useStrict = $useStrict;
        $this->prefix = $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        if ($this->useStrict == true) {
            $event->getClassMetadata()->table["schema"] = $this->prefix;
        } else {
            $schemaName = $event->getClassMetadata()->getSchemaName();
            if ($schemaName == null) {
                $namespace = explode("\\", $event->getClassMetadata()->namespace);
                $dbName = trim($namespace[0]);
                if ($dbName == "Ainias") {
                    $dbName = trim($namespace[1]);
                }

                if ($dbName == "") {
                    $dbName = $this->prefix;
                } else {
                    $dbName = $this->prefix. '_' . $dbName;
                }
                $event->getClassMetadata()->table["schema"] = $dbName;
                foreach ($event->getClassMetadata()->associationMappings as &$mapping)
                {
                    $mapping["joinTable"]["schema"] = $dbName;
                }
            }
        }
    }
}