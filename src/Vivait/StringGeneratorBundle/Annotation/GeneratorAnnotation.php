<?php

namespace Vivait\StringGeneratorBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * Class StringGenerator
 * @package Vivait\StringGeneratorBundle\Annotation
 */
class GeneratorAnnotation extends Annotation
{
    public $callbacks = [];
    /**
     * @var
     */
    public $generator;

    /**
     * @var int
     */
    public $length = 8;

    /**
     * @var boolean
     */
    public $unique = true;

    public $override = true;
}
