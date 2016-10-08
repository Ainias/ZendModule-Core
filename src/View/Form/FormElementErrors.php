<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 17.07.16
 * Time: 19:50
 */

namespace Ainias\Core\View\Form;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

class FormElementErrors extends OriginalFormElementErrors
{
//    protected $messageOpenFormat      = '<ul%s><li class="input-error">';
//    protected $messageSeparatorString = '</li><li class="input-error">';
//    protected $messageCloseString     = '</li></ul>';

    protected $messageOpenFormat      = '<small class = "form-error is-visible" %s>';
    protected $messageSeparatorString = '<br/>';
    protected $messageCloseString     = '</small>';
}