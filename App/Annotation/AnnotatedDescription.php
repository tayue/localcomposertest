<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"ALL"})
 */
class AnnotatedDescription
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $desc;

    public function __construct(array $data)
    {
        print_r($data);

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

}