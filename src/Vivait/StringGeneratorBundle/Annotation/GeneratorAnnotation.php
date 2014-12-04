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
    /**
     * @var array
     */
    public $callbacks = [];

    /**
     * @var array
     */
    public $options = [];

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

    /**
     * @var bool
     */
    public $override = true;
}
