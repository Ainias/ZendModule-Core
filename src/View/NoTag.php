<?php

namespace Ainias\Core\View;

use Zend\View\Helper\AbstractHelper;

class NoTag extends AbstractHelper
{
    const FORBIDDEN_TAGS = "iframe|frameset|frame";

    public function __invoke($text, $allowEvents = false)
    {
        return $this->escapeTags($text);
    }

    public function escapeTags($text)
    {
        return preg_replace("/[<]([\\s]*\\/?[\\s]*(".self::FORBIDDEN_TAGS.")[^<]*)[>]/ix", "&lt;$1&gt;", $text);
    }
}