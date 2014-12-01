<?php

namespace Vivait\StringGeneratorBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * Class StringGenerator
 * @package Vivait\StringGeneratorBundle\Annotation
 */
class StringGenerator extends Annotation
{
    /**
     * @var string
     */
    public $prefix;

    /**
     * @var int
     */
    public $length = 8;

    /**
     * @var boolean
     */
    public $unique = true;

    /**
     * @var string
     */
    public $separator = "-";

    /**
     * @var string
     */
    public $prefix_callback;
}