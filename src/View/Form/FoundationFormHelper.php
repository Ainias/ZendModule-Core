<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 23.07.16
 * Time: 13:49
 */

namespace Ainias\Core\View\Form;

use Ainias\Core\Form\MyForm;
use Zend\Form\Element;
use Zend\View\Helper\AbstractHelper;

class FoundationFormHelper extends AbstractHelper
{
    public function __invoke(MyForm $form = null, $singleColumn = false)
    {
        if ($form != null) {
            return $this->render($form, $singleColumn);
        }
        return $this;
    }

    public function render(MyForm $form, $singleColumn = false)
    {
        $elementString = $this->renderElements($form->getElements(), $singleColumn);
        $output = $this->getView()->form()->openTag($form).$elementString.$this->getView()->form()->closeTag();
        return $output;
    }

    public function renderElements($elements, $singleColumn = false)
    {
        $elementSting = "";
        $elements = array_values($elements);
        $numberElements = count($elements);
        for ($i = 0; $i < $numberElements; $i++) {
            if ($i % 2 == 0 || $singleColumn) {
                $elementSting .= "<div class = 'row' data-equalizer>";
            }
            $elementSting .= '<div class="large-5 medium-6 small-12 columns" data-equalizer-watch>';
            if ($elements[$i] instanceof Element\Submit)
            {
                $elementSting .= "<br/>";
            }
            $elementSting .= $this->renderElement($elements[$i]);
            $elementSting .= "</div>";
            if ($i % 2 == 1 || $singleColumn) {
                $elementSting .= "</div>";
            }
        }
        return $elementSting;
    }

    public function renderElement(Element $element)
    {
        return $this->getView()->formRow($element);
    }
}