<?php

namespace Ainias\Core\View;

use Zend\Form\View\Helper\AbstractHelper;

class FormatDatetimeGerman extends AbstractHelper
{
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

    public function __invoke(\DateTime $dateTime = null, $format = self::FORMAT_GERMAN_DATE_SHORT)
    {
        if ($dateTime == null)
        {
            return $this;
        }
        return date($format, $dateTime->getTimestamp());
    }

    public function dateLong(\DateTime $dateTime)
    {
        return $this->getGermanDayOfWeek($dateTime, true)
            .", der ".$dateTime->format("d")." "
            . $this->getGermanMonthName($dateTime, true). " "
            . $dateTime->format("Y");
    }

    public function dateMiddle(\DateTime $dateTime)
    {
        return $this->getGermanDayOfWeek($dateTime)
        .", ".$dateTime->format("d")." "
        . $this->getGermanMonthName($dateTime). ". "
        . $dateTime->format("Y");
    }

    public function getGermanMonthName(\DateTime $dateTime, $long = false)
    {
        switch ($dateTime->format("n"))
        {
            case 1:
            {
                return ($long)?"Januar":"Jan";
            }
            case 2:
            {
                return ($long)?"Februar":"Feb";
            }
            case 3:
            {
                return ($long)?"März":"Mrz";
            }
            case 4:
            {
                return ($long)?"April":"Apr";
            }
            case 5:
            {
                return ($long)?"Mai":"Mai";
            }
            case 6:
            {
                return ($long)?"Juni":"Jun";
            }
            case 7:
            {
                return ($long)?"Juli":"Jul";
            }
            case 8:
            {
                return ($long)?"August":"Aug";
            }
            case 9:
            {
                return ($long)?"September":"Sep";
            }
            case 10:
            {
                return ($long)?"Oktober":"Okt";
            }
            case 11:
            {
                return ($long)?"November":"Nov";
            }
            case 12:
            {
                return ($long)?"Dezember":"Dez";
            }
        }
        return "";
    }

    public function getGermanDayOfWeek(\DateTime $dateTime, $long = false)
    {
        switch ($dateTime->format("w"))
        {
            case 0:
            {
                return ($long)?"Sonntag":"So";
            }
            case 1:
            {
                return ($long)?"Montag":"Mo";
            }
            case 2:
            {
                return ($long)?"Dienstag":"Di";
            }
            case 3:
            {
                return ($long)?"Mittwoch":"Mi";
            }
            case 4:
            {
                return ($long)?"Donnerstag":"Do";
            }
            case 5:
            {
                return ($long)?"Freitag":"Fr";
            }
            case 6:
            {
                return ($long)?"Samstag":"Sa";
            }
        }
        return "";
    }
}