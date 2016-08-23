<?php
/**
 * Created by PhpStorm.
 * User: silas
 * Date: 20.07.16
 * Time: 17:43
 */

namespace Ainias\Core\Model\Doctrine;


/**
 * Class DiscriminatorEntry
 * @package Application\Model\Doctrine
 * @Annotation
 */
class DiscriminatorEntry
{
    private $value;

    public function __construct(array $data)
    {
        $this->value = $data['value'];
    }

    public function getValue()
    {
        return $this->value;
    }
}