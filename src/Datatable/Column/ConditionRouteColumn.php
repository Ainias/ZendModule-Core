<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 04.10.16
 * Time: 15:19
 */

namespace Ainias\Core\Datatable\Column;

class ConditionRouteColumn extends RouteColumn
{


    public function buildCell($row, $index)
    {
        if ($row[$this->getName()] == true)
        {
            return parent::buildCell($row, $index); // TODO: Change the autogenerated stub
        }
        else
        {
            $row[$this->getName()] = "";
            return Column::buildCell($row, $index);
        }
    }
}