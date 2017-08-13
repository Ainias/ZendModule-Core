<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 24.04.17
 * Time: 12:54
 */

namespace Ainias\Core\Controller;

use Ainias\Core\Controller\ServiceActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class JsonController extends ServiceActionController
{
    public function onDispatch(MvcEvent $e)
    {
        $this->layout("layout/onlyContent");

        $result = parent::onDispatch($e);
        if ($result instanceof ViewModel) {
            $result->setTemplate("ajax/json");
        }
        else if (is_array($result))
        {
            $result = new ViewModel(["json" => $result]);
            $result->setTemplate("ajax/json");
        }
        $e->setResult($result);
        return $result;
    }
}