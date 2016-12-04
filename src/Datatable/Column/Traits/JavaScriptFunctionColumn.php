<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 06.10.16
 * Time: 09:19
 */

namespace Ainias\Core\Datatable\Column\Traits;


trait JavaScriptFunctionColumn
{
    private $javaScriptFunctionName;

    public function __construct($name = "", $options = array())
    {
        $this->javaScriptFunctionName = NULL;
        $this->addClass("clickable");
        parent::__construct($name, $options);
    }

    public function buildJsArray()
    {
        $array = parent::buildJsArray();
        $javasScriptFunctionName = $this->getJavaScriptFunctionName();
        $array["fnCreatedCell"] = <<<JS
 		function(nTd, sData, oData, iRow, iCol)
                {
                    $(nTd).first().click(function(){
                        $javasScriptFunctionName(iRow, iCol, nTd, sData, oData);
                    });
                }
JS;
        return $array;
    }


    public function setOptions(array $options)
    {
        parent::setOptions($options);

        isset($options["javaScriptFunctionName"]) && $this->setJavaScriptFunctionName($options["javaScriptFunctionName"]);
    }

    /**
     * @return null
     */
    public function getJavaScriptFunctionName()
    {
        return $this->javaScriptFunctionName;
    }

    /**
     * @param null $javaScriptFunctionName
     */
    public function setJavaScriptFunctionName($javaScriptFunctionName)
    {
        $this->javaScriptFunctionName = $javaScriptFunctionName;
    }
}