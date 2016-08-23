<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 07.07.16
 * Time: 20:45
 */

namespace Ainias\Core\View;


use Zend\View\Helper\AbstractHelper;

class NoJs extends AbstractHelper
{
    const EVENT_REGEX = "afterprint|beforeprint|beforeunload|error|hashchange|load|message|offline|online|pagehide|pageshow|popstate|resize|storage|unload|blur|change|contextmenu|focus|input|invalid|reset|search|select|submit|keydown|keypress|keyup|click|dblclick|drag|dragend|dragenter|dragleave|dragover|dragstart|drop|mousedown|mousemove|mouseout|mouseover|mouseup|mousewheel|scroll|wheel|copy|cut|paste|abort|canplay|canplaythrough|cuechange|durationchange|emptied|ended|error|loadeddata|loadedmetadata|loadstart|pause|play|playing|progress|ratechange|seeked|seeking|stalled|suspend|timeupdate|volumechange|waiting|error|show|toggle";

    public function __invoke($text, $allowEvents = false)
    {
        $returnText = $this->escapeScriptTag($text);
        if (!$allowEvents)
        {
            $returnText = $this->escapeEvents($returnText);
        }
        return $returnText;
    }

    public function escapeScriptTag($text)
    {
        return preg_replace("/[<]([\\s]*\\/?[\\s]*script[^<]*)[>]/ix", "&lt;$1&gt;", $text);
    }

    public function escapeEvents($text)
    {
        return preg_replace("/(on)(".self::EVENT_REGEX.")/ix", "$1&zwnj;$2", $text);
    }
}