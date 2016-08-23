<?php
/**
 * Created by PhpStorm.
 * User: Silas
 * Date: 20.09.2015
 * Time: 13:05
 */

namespace Ainias\Core\Datatable\Column;

class JavaScriptFunctionColumn extends PictureEnumColumn //Für die Userverwaltung
{
	private $javaScriptFunctionName;

	public function __construct($name = "", $options = array())
	{
		$this->javaScriptFunctionName = NULL;
		parent::__construct($name, $options);
	}

	public function buildJsArray()
	{
       $this->setImgClass("");

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