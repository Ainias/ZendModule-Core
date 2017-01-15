<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 10.02.15
 * Time: 17:38
 */

namespace Ainias\Core\View;

use Ainias\Core\Datatable\Column\Column;
use Ainias\Core\Datatable\Datatable;
use Zend\View\Helper\AbstractHelper;

class DatatablesHelper extends AbstractHelper
{
	public function __invoke(Datatable $datatable)
	{
		$this->prepareData($datatable);
		$this->appendScriptFiles();

		$datatable->prepare($this->getView());
		$script = $datatable->buildJs();
		$this->getView()->inlineScript()->appendScript($script);
		
		return $datatable->build();
	}

	private function prepareData(Datatable $datatable)
	{
		$content = $datatable->getContent();
		$columns = $datatable->getColumns();
		foreach ($content as $index => &$item)
		{
			/** @var Column $column */
			foreach ($columns as $column)
			{
				if (!isset($item[$column->getName()]))
				{
					$item[$column->getName()] = "";
				}
			}
		}
		$datatable->setContent($content);
	}

	private function appendScriptFiles()
	{
		$this->getView()->inlineScript()->appendFile($this->getView()->basePath('/3rdParty/Datatable/datatables.min.js'));
		$this->getView()->inlineScript()->appendFile($this->getView()->basePath('/js/myDatatable.js'));
		$this->getView()->headLink()->appendStylesheet($this->getView()->basePath("/3rdParty/Datatable/datatables.min.css"));
		$this->getView()->headLink()->appendStylesheet($this->getView()->basePath("/css/datatable.css"));
	}
}