<?php

namespace Ainias\Core\View;

use Zend\Form\View\Helper\AbstractHelper;

class FormatDatetimeGerman extends AbstractHelper
{
    const TIMEZONE_GERMAN = "Europe/Berlin";

    const FORMAT_GERMAN_DATE_SHORT = "d.m.Y";
    const FORMAT_GERMAN_DATE_SHORT_TIME_SHORT = "d.m.Y H:i";
    const FORMAT_GERMAN_DATE_SHORT_TIME_LONG = "d.m.Y H:i:s";
    const FORMAT_TIME_SHORT = "H:i";
    const FORMAT_TIME_LONG = "H:i:s";
    const FORMAT_GERMAN_DATE_MIDDLE = "D, d. M. Y";
    const FORMAT_GERMAN_DATE_MIDDLE_TIME_SHORT = "D, d. M. Y H:i";
    const FORMAT_GERMAN_DATE_MIDDLE_TIME_LONG = "D, d. M. Y H:i:s";
    const FORMAT_GERMAN_DATE_LONG = "l, d. F Y";
    const FORMAT_GERMAN_DATE_LONG_TIME_SHORT = "l, d. F Y H:i";
    const FORMAT_GERMAN_DATE_LONG_TIME_LONG = "l, d. F Y H:i:s";

    public function __invoke(\DateTime $dateTime, $format = self::FORMAT_GERMAN_DATE_SHORT)
    {
        if ($dateTime == null || $format == null)
        {
            throw new \Exception("Missing parameter dateTime or format in ".__FILE__." on line ".__LINE__);
        }
        $oldTimeZone = date_default_timezone_get();
        date_default_timezone_set(self::TIMEZONE_GERMAN);
        $formatedDate =  date($format, $dateTime->getTimestamp());
        date_default_timezone_set($oldTimeZone);
        return $formatedDate;
    }
}