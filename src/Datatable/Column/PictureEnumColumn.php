<?php
/**
 * Created by PhpStorm.
 * User: Silas
 * Date: 19.09.2015
 * Time: 17:18
 */

namespace Ainias\Core\Datatable\Column;

class PictureEnumColumn extends EnumColumn
{
    /** @var  string */
    private $renderFunction;

    /** @var  string */
    private $imgClass;

    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);
        $this->renderFunction = "";
        $this->imgClass = "";
    }

    public function setOptions(array $options)
    {
        parent::setOptions($options);
        isset($options["renderFunction"]) && $this->setRenderFunction($options["renderFunction"]);
        isset($options["imgClass"]) && $this->setImgClass($options["imgClass"]);
    }


    /**
     * @return string
     */
    public function getRenderFunction(): string
    {
        return $this->renderFunction;
    }

    /**
     * @param string $renderFunction
     */
    public function setRenderFunction(string $renderFunction)
    {
        $this->renderFunction = $renderFunction;
    }

    public function buildJsArray()
    {
        $array = parent::buildJsArray();
        $basePath = $this->phpRenderer->basePath("/img");
        $renderFunction = $this->getRenderFunction();
        $imgClass = $this->getImgClass();
        $array["mRender"] = <<<JS
        function(value, type, full, td)
        {
            $renderFunction
            return "<img src = '$basePath/"+value+"' class = 'datatableImg $imgClass'>";
        }
JS;
        return $array;
    }

    /**
     * @return string
     */
    public function getImgClass(): string
    {
        return $this->imgClass;
    }

    /**
     * @param string $imgClass
     */
    public function setImgClass(string $imgClass)
    {
        $this->imgClass = $imgClass;
    }
} 