<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 28.01.17
 * Time: 16:14
 */

namespace Ainias\Core\View;


use Zend\View\Helper\AbstractHelper;

class MyEscapeHelper extends AbstractHelper
{
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param array $array
     * @return array
     */
    public function escapeHtmlArray($array)
    {
        foreach ($array as &$value)
        {
            if (is_array($value))
            {
                $value = $this->escapeHtmlArray($value);
            }
            else
            {
                $value = $this->getView()->escapeHtml($value);
            }
        }
        return $array;
    }
}