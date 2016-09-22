<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 23.09.16
 * Time: 01:50
 */

namespace Ainias\Core\View;

use Zend\Form\View\Helper\AbstractHelper;

class PriceFormatter extends AbstractHelper
{
    public function __invoke($price)
    {
        return number_format(floatval($price), 2, ",", ".")." €";
    }
}