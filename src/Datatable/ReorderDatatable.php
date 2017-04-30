<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 21.02.15
 * Time: 14:45
 */

namespace Ainias\Core\Datatable;


class ReorderDatatable extends Datatable
{
	private $reorderRouteName;
	private $reorderRouteParams;
	/** @var  boolean */
	private $usePositionColumns;

	public function __construct($id, $options = array())
	{
		$this->setReorderRouteName("");
		$this->setReorderRouteParams(array());

		parent::__construct($id, $options);
		$this->setSortable(false);
	}

    protected function buildJsInitObject()
    {
       $initObject = parent::buildJsInitObject();
       if ($this->usePositionColumns)
       {
           $initObject .= <<<JS
        dataTableInitObject.rowReorder = true;
JS;
       }
       else
       {
           $initObject .= <<<JS
        dataTableInitObject.rowReorder = {
            selector: 'tr'    
        };
JS;
       }

       return $initObject;
    }


    protected function buildJsAdditionalFunctions()
	{
		$id = $this->getId();
		$url = $this->phpRenderer->url($this->getReorderRouteName(), $this->getReorderRouteParams());

		$script = <<<JS
	$("#$id").DataTable().on('row-reorder', function(event, diff, edit){
	    var changes = [];
	    $(diff).each(function(index){
	        changes[index] = {
	            newData: this.newData,
	            oldData: this.oldData,
	            newPosition: this.newPosition,
	            oldPosition: this.oldPosition
	        }
	    });
		$.post("$url", {newPositions: changes});
	});
JS;
		return $script;
	}


	public function setOptions(array $options)
	{
		if (isset($options["reorderRouteName"]))
		{
			$this->setReorderRouteName($options["reorderRouteName"]);
		}
		if (isset($options["reorderRouteParams"]))
		{
			$this->setReorderRouteParams($options["reorderRouteParams"]);
		}
		parent::setOptions($options);
	}

	/**
	 * @return mixed
	 */
	public function getReorderRouteName()
	{
		return $this->reorderRouteName;
	}

	/**
	 * @param mixed $reorderRouteName
	 */
	public function setReorderRouteName($reorderRouteName)
	{
		$this->reorderRouteName = $reorderRouteName;
	}

	/**
	 * @return mixed
	 */
	public function getReorderRouteParams()
	{
		return $this->reorderRouteParams;
	}

	/**
	 * @param mixed $reorderRouteParams
	 */
	public function setReorderRouteParams(array $reorderRouteParams)
	{
		$this->reorderRouteParams = $reorderRouteParams;
	}

    /**
     * @return bool
     */
    public function isUsePositionColumns()
    {
        return $this->usePositionColumns;
    }

    /**
     * @param bool $usePositionColumns
     */
    public function setUsePositionColumns($usePositionColumns)
    {
        $this->usePositionColumns = $usePositionColumns;
    }
} 